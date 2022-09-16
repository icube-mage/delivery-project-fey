<?php

namespace App\Http\Livewire\Menu;

use App\Imports\FileDataImport;
use App\Models\Brand;
use App\Models\CatalogPrice;
use App\Models\CatalogPriceTemp;
use App\Models\Marketplace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
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
        // dd($marketplaceName);
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
        $avg = 0;
        $generateHash = Str::uuid();
        foreach ($cPriceTemp as $cpt) {
            // Ganti insert temp ke price
            $cPrice = [
                'upload_hash' => $generateHash,
                'sku' => $cpt->sku,
                'name' => $cpt->name,
                'rrp' => $cpt->rrp,
                'cbp' => $cpt->cbp,
                'user_id' => $cpt->user_id,
                'brand' => $cpt->brand,
                'marketplace' => $cpt->marketplace,
                'start_date' => $cpt->start_date,
            ];
            CatalogPrice::create($cPrice);

            // $countDataPrice = CatalogPrice::where('sku', '=', $cpt->sku)->count();
            // $totalCbpPrice = CatalogPrice::where('sku', '=', $cpt->sku)->sum('cbp');
            // $avgPrice = $totalCbpPrice / $countDataPrice;

            // $countDataTemp = CatalogPriceTemp::where('sku', '=', $cpt->sku)
            // ->where('brand', '=', $cpt->brand)
            // ->where('marketplace', '=', $cpt->marketplace)->count();
            // $totalCbpTemp = CatalogPriceTemp::where('sku', '=', $cpt->sku)->sum('cbp');
            // $avgTemp = $totalCbpTemp / $countDataTemp;
            // $checkPriceAvg = CatalogPriceAvg::where('sku', '=', $cpt->sku)->get();

            // Ineserting data to catalog_price_averages if empty
            // if ($checkPriceAvg->isEmpty()) {
            //     $cPriceAvg = [
            //         'sku' => $cpt->sku,
            //         'name' => $cpt->name,
            //         'rrp' => $cpt->rrp,
            //         'cbp' => $avgTemp,
            //         'user_id' => $cpt->user_id,
            //         'brand' => $cpt->brand,
            //         'marketplace' => $cpt->marketplace,
            //         'start_date' => $cpt->start_date,
            //     ];
            //     CatalogPriceAvg::create($cPriceAvg);
            // }

            // Compare data if average from catalog_price_averages are equal with average in catalog_price_temp then insert to catalog_price
            // $totalCbpAvg = CatalogPriceAvg::where('sku', '=', $cpt->sku)->sum('cbp');
            // if ($avgTemp == $totalCbpAvg) {
            //     $checkPriceStartDate = CatalogPrice::where('sku', '=', $cpt->sku)->pluck('start_date')->all();
            //     $checkTempStartDate = CatalogPriceTemp::where('sku', '=', $cpt->sku)->pluck('start_date')->all();
            // dd($checkTempStartDate != $checkTempStartDate);
            // if($checkPriceStartDate != $checkTempStartDate){
            //     $cPriceAvg = [
            //         'sku' => $cpt->sku,
            //         'name' => $cpt->name,
            //         'rrp' => $cpt->rrp,
            //         'cbp' => $avgTemp,
            //         'user_id' => $cpt->user_id,
            //         'brand' => $cpt->brand,
            //         'marketplace' => $cpt->marketplace,
            //         'start_date' => $cpt->start_date,
            //     ];
            //     CatalogPrice::create($cPriceAvg);
            // }

            // dd($countDataPrice);
            // $avgExist = CatalogPrice::where('sku', '=', $cpt->sku)->get();
            // if(!$avgExist->isEmpty()){
            //     $countDataPrice = CatalogPrice::where('sku', '=', $cpt->sku)->count();
            //     $totalCbpPrice = CatalogPrice::where('sku', '=', $cpt->sku)->sum('cbp');
            //     $avgPrice = $totalCbpPrice / $countDataPrice;
            //     CatalogPriceAvg::where('sku', '=', $cpt->sku)->update(['cbp'=>$avgPrice]);
            // }
            // }

            // $getCbpPriceData = CatalogPrice::where('sku', '=', $cpt->sku)->pluck('name');
            // dd($getCbpPriceData);
        }

        return back()->withStatus('File imported succesfully');
    }

    public function test()
    {
        $this->validate([
            'brand' => 'required',
            'marketplace' => 'required',
            'file' => 'required|mimes:xlsx, csv, xls',
        ]);
        dd($this->brand, $this->file);
    }

    public function userId()
    {
        $this->userId = Auth::user()->id;
    }

    public function brand()
    {
        $this->brand;
    }

    public function marketplace()
    {
        $this->brand;
    }
}
