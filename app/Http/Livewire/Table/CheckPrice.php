<?php

namespace App\Http\Livewire\Table;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\HistoryUser;
use Illuminate\Support\Str;
use App\Models\CatalogPrice;
use Livewire\WithPagination;
use App\Models\CatalogPriceAvg;
use App\Models\CatalogPriceTemp;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class CheckPrice extends Component
{
    use WithPagination;

    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $dataTemp = [
        [
            'id' => '',
            'sku' => '',
            'name' => '',
            'price' => '',
            'discount' => '',
            'average_discount' => '',
            'is_whitelist' => ''
        ]
    ];

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function(){
            return $this->currentPage;
        });
    }

    public function sortBy($field)
    {
        if($this->sortField === $field)
        {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc':'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function changePrice($id, $newPrice){
        CatalogPriceTemp::where('id',$id)->update(['discount_price' => (int)$newPrice]);

        session()->flash('message', 'Price update success');
    }

    public function changeWhitelist($id, $whitelistStatus){
        CatalogPriceTemp::where('id',$id)->update(['is_whitelist' => $whitelistStatus]);

        session()->flash('message', 'Whitelist update success');
    }

    public function verifyData(){
        $updatedCatalogPriceTemp = CatalogPriceTemp::where('user_id','=',Auth::user()->id)->whereColumn('updated_at', '>' , 'created_at')->orderBy('updated_at', 'desc')->get();

        foreach ($updatedCatalogPriceTemp as $catPriceTemp) {
            # code...            
            $avgPriceCat = CatalogPriceAvg::where('sku', $catPriceTemp->sku)->where('brand', $catPriceTemp->brand)->where('marketplace', $catPriceTemp->marketplace)->pluck('average_price')->first();

            $totalDataAvgPrice = CatalogPriceAvg::where('sku', $catPriceTemp->sku)->where('brand', $catPriceTemp->brand)->where('marketplace', $catPriceTemp->marketplace)->pluck('total_record')->first();

            $totalPriceTemp = CatalogPriceTemp::where('sku', $catPriceTemp->sku)->where('brand', $catPriceTemp->brand)->where('marketplace', $catPriceTemp->marketplace)->sum('discount_price');
            
            $countNewAvg = (($avgPriceCat * $totalDataAvgPrice) + $totalPriceTemp) / ($totalDataAvgPrice + 1);
            
            if($catPriceTemp->is_whitelist == false){
                CatalogPriceAvg::where('sku', $catPriceTemp->sku)->where('brand', $catPriceTemp->brand)->where('marketplace', $catPriceTemp->marketplace)->update(['average_price' => $countNewAvg]);
            }
        }

        
        $dataCatalogPriceTemp = CatalogPriceTemp::where('user_id','=',Auth::user()->id)->get();
        
        foreach ($dataCatalogPriceTemp as $cpt){
            $averagePrice = CatalogPriceAvg::where('user_id','=',Auth::user()->id)->where('sku','=',$cpt->sku)->where('marketplace','=',$cpt->marketplace)->where('brand','=',$cpt->brand)->pluck('average_price')->first();
            
            if($cpt->discount_price == $averagePrice || $cpt->discount_price > $averagePrice){
                // Set is_negative false to product with price equal or over average
                CatalogPriceTemp::where('sku', $cpt->sku)->where('discount_price', '>=', $averagePrice)->update(['is_negative' => false]);
            }

            if($cpt->is_negative == false){
                $generateHash = Str::uuid(); 
                $catPrice = [
                    'upload_hash' => $generateHash,
                    'sku' => $cpt->sku,
                    'product_name' => $cpt->product_name,
                    'retail_price' => $cpt->retail_price,
                    'discount_price' => $cpt->discount_price,
                    'user_id' => $cpt->user_id,
                    'brand' => $cpt->brand,
                    'marketplace' => $cpt->marketplace,
                    'is_whitelist' => $cpt->is_whitelist,
                    'is_negative' => $cpt->is_negative,
                    'start_date' => $cpt->start_date,
                ];
                CatalogPrice::create($catPrice);
                session()->flash('message', 'Data verified');
            } else{
                session()->flash('error', 'Please check the price again');
            }
        }
        
    }
    
    public function render()
    {
        $catalogTemp = CatalogPriceTemp::where('user_id','=',Auth::user()->id)->get();
        $dataCatalog = [];
        $brand = "";
        $countDataTemp = "";
        $extrasHistory = [];
        $marketplace = "";
        $totalError = "";
        $userId = "";
        
        
        foreach ($catalogTemp as $items) {
            $brand = $items->brand;
            $marketplace = $items->marketplace;
            $userId = $items->user_id;
            $countDataTemp = CatalogPriceTemp::where('sku', '=', $items->sku)
            ->where('brand', '=', $items->brand)
            ->where('marketplace', '=', $items->marketplace)->count();

            $totalDiscountPriceTemp = CatalogPriceTemp::where('sku', '=', $items->sku)
            ->where('brand', '=', $items->brand)
            ->where('marketplace', '=', $items->marketplace)->sum('discount_price');

            $avgTemp = $totalDiscountPriceTemp / $countDataTemp;

            $averagePrice = CatalogPriceAvg::where('user_id','=',Auth::user()->id)
            ->where('sku','=',$items->sku)->where('marketplace','=',$items->marketplace)->where('brand','=',$items->brand)->pluck('average_price')->first();

            if($items->discount_price < $averagePrice){
                $dataCatalog[] = array(
                    'id' => $items->id,
                    'sku' => $items->sku,
                    'product_name' => $items->product_name,
                    'price' => $items->discount_price,
                    'discount' => $avgTemp,
                    'average_discount' => $averagePrice,
                    'is_whitelist' => $items->is_whitelist
                );

                // Set is_negative true to product with price under average
                CatalogPriceTemp::where('sku', $items->sku)->where('discount_price', '<', $averagePrice)->update(['is_negative' => true]);
                
                $extrasHistory[] = array(
                    'sku' => $items->sku,
                    'price' => $items->discount_price,
                    'average_discount' => $averagePrice
                );
            } 
        }
        $this->dataTemp = $dataCatalog ?? '';
        
        // Insert data to history
        $totalError = count($dataCatalog);
        $historyData = [
            'user_id' => $userId,
            'brand' => $brand,
            'marketplace' => $marketplace,
            'total_records' => $countDataTemp,
            'false_price' => $totalError,
            'extras' => json_encode($extrasHistory)
        ];
        HistoryUser::create($historyData);

        return view('livewire.table.check-price');
    }
}
