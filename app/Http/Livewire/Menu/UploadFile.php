<?php

namespace App\Http\Livewire\Menu;

use App\Models\Brand;
use Livewire\Component;
use App\Models\Marketplace;
use Illuminate\Support\Str;
use App\Models\CatalogPrice;
use App\Models\Configuration;
use Livewire\WithFileUploads;
use App\Imports\FileDataImport;
use App\Models\CatalogPriceAvg;
use App\Models\CatalogPriceTemp;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class UploadFile extends Component
{
    use WithFileUploads;

    public $title = 'Upload File';
    public $userId;
    public $brand;
    public $marketplace;
    public $file;
    public $isUploaded = false;
    public $errorMsg;
    public $submitBtn = false;

    public function render()
    {
        $brands = Brand::all();
        $marketplaces = Marketplace::all();
        return view('livewire.menu.upload-file', ['brands' => $brands, 'marketplaces' => $marketplaces]);
    }

    public function updatedMarketplace(){
        $configName = $this->marketplace."_column_map";
        $getConfigTokped = Configuration::where("key",$configName)->first();
        if($getConfigTokped == null){
            $this->errorMsg = "Please set the config!";
            $this->submitBtn = false;
        } else{
            $this->submitBtn = true;
        }
    }

    public function submit()
    {
        $this->validate([
            'brand' => 'required',
            'marketplace' => 'required',
            'file' => 'required|mimes:xlsx, csv, xls',
        ]);
        CatalogPriceTemp::truncate();
        try{
            $import = new FileDataImport($this->brand, $this->marketplace);
            Excel::import($import, $this->file);
        } catch(\Exception $e){
            $this->errorMsg = $e->getMessage();
        }

        $this->userId = Auth::user()->id;
        $cPriceTemp = CatalogPriceTemp::where('user_id', $this->userId)
            ->where('brand', $this->brand)
            ->where('marketplace', $this->marketplace)
            ->get();

        foreach ($cPriceTemp as $cpt) {
            $countDataTemp = CatalogPriceTemp::where('sku', $cpt->sku)
                ->where('brand', $cpt->brand)
                ->where('marketplace', $cpt->marketplace)->count();
            $totalDiscountPriceTemp = CatalogPriceTemp::where('sku', $cpt->sku)->sum('discount_price');
            $avgTemp = $totalDiscountPriceTemp / $countDataTemp;
            $checkPriceAvg = CatalogPriceAvg::where('sku', $cpt->sku)->where('brand', $cpt->brand)->where('marketplace', $cpt->marketplace)->get();

            // Inserting data to catalog_price_averages if empty
            if ($checkPriceAvg->isEmpty()) {
                $cPriceAvg = [
                    'sku' => $cpt->sku,
                    'average_price' => $avgTemp,
                    'total_record' => $countDataTemp,
                    'user_id' => $cpt->user_id,
                    'brand' => $cpt->brand,
                    'marketplace' => $cpt->marketplace,
                    'start_date' => $cpt->start_date,
                ];
                CatalogPriceAvg::create($cPriceAvg);
            }
            
        }

        $this->isUploaded  = true;
        $this->emit('setUploaded',$this->isUploaded);

        return back()->withStatus('File upload success');
    }
}
