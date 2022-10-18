<?php

namespace App\Http\Livewire\Table;

use Livewire\Component;
use App\Models\HistoryUser;
use Illuminate\Support\Str;
use App\Models\CatalogPrice;
use Livewire\WithPagination;
use App\Models\CatalogPriceAvg;
use App\Models\CatalogPriceTemp;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckPrice extends Component
{
    use WithPagination;

    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $dataTemp;
    public $errorIds;
    public $brand;
    public $marketplace;
    public $errorData;
    public $firstLoad = true;
    public $submitBtn = false;
    public $beforeVerified = true;
    public $downloadBtn = 'Download';
    protected $listeners = ['store', 'clearTemp'];

    public function setDownloadBtn($label){
        $this->downloadBtn = $label;
    }
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

    public function alertConfirm()
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'text' => 'Check the data twice before submit!',
            'title' => 'Submit your changes'
        ]);
    }

    public function changePrice($index, $newPrice){
        if($newPrice==null){
            return session()->flash('error', 'Price value can not be null');
        }
        if ((int)$newPrice >= (int)$this->dataTemp[$index]['average_discount']) {
            $this->dataTemp[$index]['is_negative'] = false;
        } else {
            $this->dataTemp[$index]['is_negative'] = true;
        }
        $this->dataTemp[$index]['price'] = $newPrice;
        CatalogPriceTemp::where('id',$this->dataTemp[$index]['id'])->update(['is_negative' => $this->dataTemp[$index]['is_negative']]);
        $this->firstLoad = false;
    }

    public function changeWhitelist($id, $whitelistStatus){
        if($whitelistStatus == false){
            $this->errorData = $this->errorData+1;
        } else {
            $this->errorData = $this->errorData-1;
        }
        CatalogPriceTemp::where('id',$id)->update(['is_whitelist' => $whitelistStatus]);
        $this->firstLoad = false;
    }

    public function clearTemp()
    {
        $this->dataTemp = [];
        $this->firstLoad = false;
        $this->beforeVerified = false;
        session()->forget('historyData');
        session()->forget('checkPriceHash');
    }

    public function store()
    {
        // update all price after fixed
        DB::beginTransaction();
        try{
            foreach($this->dataTemp as $fixed){
                CatalogPriceTemp::where('id',$fixed['id'])->update([
                    'discount_price' => $fixed['price'],
                ]);
            } 
            DB::commit();   
        } catch(\Exception $e){
            //if there is an error/exception in the above code before commit, it will rollback
            DB::rollBack();
            $this->submitBtn = false;
            return session()->flash('error', 'Incorrect price value, please check again!');
        } 
        $preCatalogPrices = [];
        CatalogPriceTemp::where('user_id', Auth::user()->id)->where('brand', $this->brand)->where('marketplace', $this->marketplace)
        ->chunk(100, function ($dataCatalogPriceTemp) use($preCatalogPrices) {
            $avgPriceCat = 0;
            $totalPriceTemp = 0;
            $sku = '';
            foreach ($dataCatalogPriceTemp as $cpt) {
                // $sku = $cpt->sku;
                // if($cpt->is_whitelist == false && $cpt->is_discount == true){
                // $avgPriceCat = CatalogPriceAvg::where('sku', $sku)
                            //     ->where('brand', $this->brand)
                            //     ->where('marketplace', $this->marketplace)
                            //     ->where('warehouse', $cpt->warehouse)
                            //     ->pluck('average_price')->first();

                // $totalDataPrice = CatalogPrice::where('sku', $cpt->sku)
                            //     ->where('brand', $this->brand)
                            //     ->where('marketplace', $this->marketplace)
                            //     ->where('warehouse', $cpt->warehouse)
                            //     ->count();
                // $catalogPriceTemp = CatalogPriceTemp::select(DB::raw('COUNT(*) AS count, SUM(discount_price) AS sum'))
                            //         ->where('sku', $cpt->sku)
                            //         ->where('brand', $this->brand)
                            //         ->where('marketplace', $this->marketplace)
                            //         ->where('warehouse', $cpt->warehouse)
                            //         ->first();

                // $totalPriceTemp = $catalogPriceTemp->sum;

                // $countPriceTemp = $catalogPriceTemp->count;

                // $countNewAvg = (($avgPriceCat * $totalDataPrice) + $totalPriceTemp) / ($totalDataPrice + $countPriceTemp);

                // CatalogPriceAvg::where('sku', $cpt->sku)
                            //     ->where('brand', $this->brand)
                            //     ->where('marketplace', $this->marketplace)
                            //     ->where('warehouse', $cpt->warehouse)
                            //     ->update([
                            //         'average_price' => $countNewAvg,
                            //         'total_record' => $totalDataPrice + $countPriceTemp
                            //     ]);
                // }

                // CatalogPrice::firstOrCreate(
                //     [
                //         'upload_hash' => session()->get('checkPriceHash'),
                //         'sku' => $cpt->sku,
                //         'user_id' => $cpt->user_id,
                //         'brand' => $cpt->brand,
                //         'marketplace' => $cpt->marketplace,
                //         'warehouse' => $cpt->warehouse,
                //     ],
                //     [
                //         'discount_price' => $cpt->discount_price,
                //         'product_name' => $cpt->product_name,
                //         'retail_price' => $cpt->retail_price,
                //         'is_whitelist' => $cpt->is_whitelist,
                //         'is_negative' => $cpt->is_negative,
                //         'start_date' => $cpt->start_date,
                //     ]
                // );
                $checkCatalogPrices = CatalogPrice::where('upload_hash', session()->get('checkPriceHash'))
                                ->where('sku', $cpt->sku)
                                ->where('user_id', $cpt->user_id)
                                ->where('brand', $cpt->brand)
                                ->where('marketplace', $cpt->marketplace)
                                ->where('warehouse', $cpt->warehouse)
                                ->first();
                if ($checkCatalogPrices != null) {
                    $preCatalogPrices[] = [
                        'upload_hash' => session()->get('checkPriceHash'),
                        'sku' => $cpt->sku,
                        'user_id' => $cpt->user_id,
                        'brand' => $cpt->brand,
                        'marketplace' => $cpt->marketplace,
                        'warehouse' => $cpt->warehouse,
                        'discount_price' => $cpt->discount_price,
                        'product_name' => $cpt->product_name,
                        'retail_price' => $cpt->retail_price,
                        'is_whitelist' => $cpt->is_whitelist,
                        'is_negative' => $cpt->is_negative,
                        'start_date' => $cpt->start_date,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        });
        CatalogPrice::insert($preCatalogPrices);
        $this->submitBtn = true;
        $this->dispatchBrowserEvent('swal:success', [
            'text' => 'All prices successfully submitted',
        ]);
    }
    
    public function render()
    {
        return view('livewire.table.check-price');
    }
}
