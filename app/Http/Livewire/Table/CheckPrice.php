<?php

namespace App\Http\Livewire\Table;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\HistoryUser;
use Illuminate\Support\Str;
use App\Models\CatalogPrice;
use Livewire\WithPagination;
use App\Exports\FileDataExport;
use App\Models\CatalogPriceAvg;
use App\Models\CatalogPriceTemp;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class CheckPrice extends Component
{
    use WithPagination;

    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $dataTemp = [
        [
            'id' => null,
            'sku' => '',
            'name' => '',
            'price' => 0,
            'discount' => 0,
            'average_discount' => 0,
            'is_whitelist' => '',
            'is_changed' => false
        ]
    ];
    public $brand;
    public $firstLoad = true;
    public $errorData;
    public $submitBtn = false;

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

    public function changePrice($index, $newPrice){
        if((int)$newPrice >= (int)$this->dataTemp[$index]['average_discount']){
            if($this->dataTemp[$index]['is_changed']){
                if((int)$this->dataTemp[$index]['price'] < (int)$this->dataTemp[$index]['average_discount']) {
                    $this->errorData = $this->errorData-1;
                }
            } else {
                $this->errorData = $this->errorData-1;
                $this->dataTemp[$index]['is_changed'] = true;
            }
        } else {
            if ($this->dataTemp[$index]['is_changed']) {
                if ((int)$this->dataTemp[$index]['price'] >= (int)$this->dataTemp[$index]['average_discount']) {
                    $this->errorData = $this->errorData+1;
                }
            }
        }
        $this->dataTemp[$index]['price'] = $newPrice;
        // CatalogPriceTemp::where('id',$dataChanged['id'])->update(['discount_price' => (int)$newPrice]);
        // session()->flash('message', 'Price update success');
    }

    public function changeWhitelist($id, $whitelistStatus){
        if($whitelistStatus == false){
            $this->errorData = $this->errorData+1;
        } else {
            $this->errorData = $this->errorData-1;
        }
        CatalogPriceTemp::where('id',$id)->update(['is_whitelist' => $whitelistStatus]);

        // session()->flash('message', 'Whitelist update success');
    }

    public function verifyData(){
        // update all price after fixed
        foreach($this->dataTemp as $fixed){
            CatalogPriceTemp::where('id',$fixed['id'])->update(['discount_price' => $fixed['price']]);
        }
        // clear after success update
        $this->dataTemp = [];        
        $dataCatalogPriceTemp = CatalogPriceTemp::where('user_id', Auth::user()->id)->get();

        $generateHash = Str::uuid(); 
        $avgPriceCat = 0;
        $totalDataAvgPrice = 0;
        $totalPriceTemp = 0;
        $sku = $band = $marketplace = '';
        foreach ($dataCatalogPriceTemp as $cpt){
            $sku = $cpt->sku;
            $brand = $cpt->brand;
            $marketplace = $cpt->marketplace;
            if($cpt->is_whitelist == false){
                $avgPriceCat = CatalogPriceAvg::where('sku', $sku)->where('brand', $brand)->where('marketplace', $marketplace)->pluck('average_price')->first();

                $totalDataAvgPrice = CatalogPriceAvg::where('sku', $cpt->sku)->where('brand', $cpt->brand)->where('marketplace', $cpt->marketplace)->pluck('total_record')->first();
                
                $totalPriceTemp = CatalogPriceTemp::where('sku', $cpt->sku)->where('brand', $cpt->brand)->where('marketplace', $cpt->marketplace)->sum('discount_price');
                
                $countPriceTemp = CatalogPriceTemp::where('sku', $cpt->sku)->where('brand', $cpt->brand)->where('marketplace', $cpt->marketplace)->count();
                
                $countNewAvg = (($avgPriceCat * $totalDataAvgPrice) + $totalPriceTemp) / ($totalDataAvgPrice + $countPriceTemp);

                CatalogPriceAvg::where('sku', $cpt->sku)->where('brand', $cpt->brand)->where('marketplace', $cpt->marketplace)->update(['average_price' => $countNewAvg]);
            }

            if($cpt->is_whitelist==false && $cpt->discount_price < $avgPriceCat){
                $this->errorData = $this->errorData+1;
                $this->dataTemp[] = array(
                    'id' => $cpt->id,
                    'sku' => $cpt->sku,
                    'product_name' => $cpt->product_name,
                    'price' => $cpt->discount_price,
                    'discount' => $totalPriceTemp / $countPriceTemp,
                    'average_discount' => $countNewAvg,
                    'is_whitelist' => $cpt->is_whitelist,
                    'is_changed' => false
                );
            } else {
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
                    'start_date' => $cpt->start_date,
                ];
                CatalogPrice::create($catPrice);
                
            }

        }
        if ($this->errorData == 0) {
            $this->submitBtn = true;
            session()->flash('message', 'Data verified');
        } else {
            $this->submitBtn = false;
            session()->flash('error', 'Please check the price again');
        }
    }
    
    public function render()
    {
        if ($this->firstLoad) {
            $catalogTemp = CatalogPriceTemp::where('user_id', '=', Auth::user()->id)->get();
            $dataCatalog = [];
            $brand = "";
            $countDataTemp = "";
            $extrasHistory = [];
            $marketplace = "";
            $totalError = "";
            $userId = "";
            $countError=0;
            $countWhitelist = 0;
            foreach ($catalogTemp as $items) {
                $brand = $items->brand;
                $marketplace = $items->marketplace;
                $userId = $items->user_id;
                $countDataTemp = CatalogPriceTemp::where('sku', $items->sku)
                ->where('brand', $items->brand)
                ->where('marketplace', $items->marketplace)->count();

                $totalDiscountPriceTemp = CatalogPriceTemp::where('sku', $items->sku)
                ->where('brand', $items->brand)
                ->where('marketplace', $items->marketplace)->sum('discount_price');

                $avgTemp = $totalDiscountPriceTemp / $countDataTemp;

                $averagePrice = CatalogPriceAvg::where('user_id', Auth::user()->id)
                ->where('sku', $items->sku)->where('marketplace', $items->marketplace)->where('brand', $items->brand)->pluck('average_price')->first();

                if ($items->discount_price < $averagePrice) {
                    $dataCatalog[] = array(
                        'id' => $items->id,
                        'sku' => $items->sku,
                        'product_name' => $items->product_name,
                        'price' => $items->discount_price,
                        'discount' => $avgTemp,
                        'average_discount' => $averagePrice,
                        'is_whitelist' => $items->is_whitelist,
                        'is_changed' => false
                    );

                    // Set is_negative true to product with price under average
                    CatalogPriceTemp::where('sku', $items->sku)->where('discount_price', '<', $averagePrice)->update(['is_negative' => true]);
                    $countError = count($dataCatalog);
                    $extrasHistory[] = array(
                        'sku' => $items->sku,
                        'price' => $items->discount_price,
                        'average_discount' => $averagePrice
                    );
                } elseif ($items->discount_price > $averagePrice) {
                    CatalogPriceTemp::where('user_id', Auth::user()->id)->whereColumn('updated_at', '>', 'created_at')->orderBy('updated_at', 'desc')->get();
                }
                if($items->is_whitelist){
                    $countWhitelist++;
                }
            }

            // dd($updatedCatalogPriceTemp);
            $this->errorData = $countError-$countWhitelist;


            // dd($countError);
            $this->dataTemp = $dataCatalog ?? '';

            // Insert data to history
            $totalError = $countError;
            $historyData = [
                'user_id' => $userId,
                'brand' => $brand,
                'marketplace' => $marketplace,
                'total_records' => $catalogTemp->count(),
                'false_price' => $totalError,
                'extras' => json_encode($extrasHistory)
            ];
            HistoryUser::create($historyData);

            $this->firstLoad = false;
            $this->brand = $brand;
            $this->marketplace = $marketplace;
        }

        return view('livewire.table.check-price');
    }
}
