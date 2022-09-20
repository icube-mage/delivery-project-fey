<?php

namespace App\Http\Livewire\Menu;

use App\Models\Brand;
use Livewire\Component;
use App\Models\Marketplace;
use Illuminate\Support\Str;
use App\Models\CatalogPrice;
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

    public function render()
    {
        $brands = Brand::all();
        $marketplaces = Marketplace::all();
        return view('livewire.menu.upload-file', ['brands' => $brands, 'marketplaces' => $marketplaces]);
    }

    public function submit()
    {
        $brandsName = Brand::where('id', (int) $this->brand)->pluck('slug')->first();
        $marketplaceName = Marketplace::where('id', (int) $this->marketplace)->pluck('slug')->first();
        $this->validate([
            'brand' => 'required',
            'marketplace' => 'required',
            'file' => 'required|mimes:xlsx, csv, xls',
        ]);
        CatalogPriceTemp::truncate();
        Excel::import(new FileDataImport($brandsName, $marketplaceName), $this->file);

        $this->userId = Auth::user()->id;
        $cPriceTemp = CatalogPriceTemp::where('user_id', '=', $this->userId)
            ->where('brand', '=', $brandsName)
            ->where('marketplace', '=', $marketplaceName)
            ->get();

        foreach ($cPriceTemp as $cpt) {
            $countDataTemp = CatalogPriceTemp::where('sku', '=', $cpt->sku)
            ->where('brand', '=', $cpt->brand)
            ->where('marketplace', '=', $cpt->marketplace)->count();
            $totalDiscountPriceTemp = CatalogPriceTemp::where('sku', '=', $cpt->sku)->sum('discount_price');
            $avgTemp = $totalDiscountPriceTemp / $countDataTemp;
            $checkPriceAvg = CatalogPriceAvg::where('sku', '=', $cpt->sku)->where('brand', '=', $cpt->brand)->where('marketplace', '=', $cpt->marketplace)->get();

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

        return back()->withStatus('File imported succesfully');
    }
}
