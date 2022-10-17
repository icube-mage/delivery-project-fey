<?php

namespace App\Http\Livewire\Menu;

use App\Models\Brand;
use Livewire\Component;
use App\Models\Marketplace;
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
        $getConfigMp = Configuration::where("key",$configName)->first();
        if($getConfigMp == null && $this->marketplace != null){
            $this->errorMsg = "Please set the config!";
            $this->submitBtn = false;
        } 

        if($this->marketplace == null){
            $this->submitBtn = false;
        }

        if($getConfigMp != null || $this->marketplace == null){
            $this->errorMsg = null;
        }

        if($this->brand != null && $this->marketplace != null && $this->file != null ) {
            if($getConfigMp == null){
                $this->submitBtn = false;
            } else{
                $this->submitBtn = true;
            }
        }
    }

    public function updatedBrand(){
        if($this->brand == null){
            $this->submitBtn = false;
        } else{
            $this->errorMsg = null;
        }

        if($this->brand != null && $this->marketplace != null && $this->file != null ) {
            $this->submitBtn = true;
        }
    }

    public function updatedFile() {
        if($this->file == null){
            $this->submitBtn = false;
        } else{
            $this->errorMsg = null;
        }

        if($this->brand != null && $this->marketplace != null && $this->file != null ) {
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
        } catch(\Exception $e){
            $this->reset();
            return $this->errorMsg = $e->getMessage();
        }
        
        try{
            Excel::import($import, $this->file);
        } catch(\Exception $e){
            if(stristr($e->getMessage(), "Please")){
                return $this->errorMsg = $e->getMessage();
            } else {
                return $this->errorMsg = "Content start are not correct, please check your configuration!";
            }
        }
        $this->userId = Auth::user()->id;
        $cPriceTemp = CatalogPriceTemp::where('user_id', $this->userId)
            ->where('brand', $this->brand)
            ->where('marketplace', $this->marketplace)
            ->get();

        $cPriceTempData = [];
        foreach ($cPriceTemp as $cpt) {
            $countDataPrice = CatalogPrice::where('sku', $cpt->sku)
                ->where('brand', $cpt->brand)
                ->where('marketplace', $cpt->marketplace)
                ->where('warehouse', $cpt->warehouse)
                ->count();
            if($countDataPrice==0){
                $countDataPrice = CatalogPriceTemp::where('sku', $cpt->sku)
                    ->where('brand', $cpt->brand)
                    ->where('marketplace', $cpt->marketplace)
                    ->where('warehouse', $cpt->warehouse)
                    ->count();
            }
            $totalDiscountPriceTemp = CatalogPriceTemp::where('sku', $cpt->sku)
                ->where('brand', $cpt->brand)
                ->where('marketplace', $cpt->marketplace)
                ->where('warehouse', $cpt->warehouse)
                ->sum('discount_price');
            $avgTemp = $totalDiscountPriceTemp / $countDataPrice;

            $checkPriceAvg = CatalogPriceAvg::where('sku', $cpt->sku)
                ->where('brand', $cpt->brand)
                ->where('marketplace', $cpt->marketplace)
                ->where('warehouse', $cpt->warehouse)
                ->get();

            // Inserting data to catalog_price_averages if empty
            if ($checkPriceAvg->isEmpty()) {
                $cPriceAvg = [
                    'sku' => $cpt->sku,
                    'average_price' => $avgTemp,
                    'total_record' => $countDataPrice,
                    'user_id' => $cpt->user_id,
                    'brand' => $cpt->brand,
                    'marketplace' => $cpt->marketplace,
                    'start_date' => $cpt->start_date,
                    'warehouse' => $cpt->warehouse,
                ];
                CatalogPriceAvg::create($cPriceAvg);
            }
            
            $cPriceTempData = $cpt;
        }

        if($cPriceTempData != null){
            $this->isUploaded  = true;
        } else{
            $this->isUploaded  = false;
        }
        $this->emit('setUploaded',$this->isUploaded);

        return back()->withStatus('File upload success');
    }
}
