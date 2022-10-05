<?php

namespace App\Http\Livewire\Table;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\HistoryUser;
use Illuminate\Support\Str;
use App\Models\CatalogPrice;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use App\Exports\FileDataExport;
use App\Models\CatalogPriceAvg;
use App\Models\CatalogPriceTemp;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;

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
            'is_whitelist' => null,
            'is_negative' => true,
            'is_changed' => false
        ]
    ];
    public $brand;
    public $firstLoad = true;
    public $errorData;
    public $submitBtn = false;
    public $beforeVerified = true;
    protected $listeners = ['store','refreshPage' => '$refresh'];

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

    public function clearTemp()
    {
        $this->dataTemp = [];
        $this->beforeVerified = false;
        session()->forget('historyData');
        session()->forget('checkPriceHash');
        session()->regenerate();
    }

    public function store(){
        // update all price after fixed
        DB::beginTransaction();
        try{
            foreach($this->dataTemp as $fixed){
                // dd($fixed['id']);
                CatalogPriceTemp::where('id',$fixed['id'])->update([
                    'discount_price' => $fixed['price'],
                ]);
            } 
            DB::commit();   
        } catch(\Exception $e){
            //if there is an error/exception in the above code before commit, it'll rollback
            DB::rollBack();
            $this->submitBtn = false;
            return session()->flash('error', 'Incorrect price value, please check again!');
        } 
        $dataCatalogPriceTemp = CatalogPriceTemp::where('user_id', Auth::user()->id)->get();
        $avgPriceCat = 0;
        $totalPriceTemp = 0;
        $sku = $brand = $marketplace = '';
        foreach ($dataCatalogPriceTemp as $cpt){
            $sku = $cpt->sku;
            $brand = $cpt->brand;
            $marketplace = $cpt->marketplace;
            if($cpt->is_whitelist == false && $cpt->is_negative == false){
                $avgPriceCat = CatalogPriceAvg::where('sku', $sku)->where('brand', $brand)->where('marketplace', $marketplace)->pluck('average_price')->first();

                // $totalDataAvgPrice = CatalogPriceAvg::where('sku', $cpt->sku)->where('brand', $cpt->brand)->where('marketplace', $cpt->marketplace)->pluck('total_record')->first();
                $totalDataPrice = CatalogPrice::where('sku', $cpt->sku)
                    ->where('brand', $cpt->brand)
                    ->where('marketplace', $cpt->marketplace)
                    ->where('warehouse', $cpt->warehouse)
                    ->count();
                
                $totalPriceTemp = CatalogPriceTemp::where('sku', $cpt->sku)
                    ->where('brand', $cpt->brand)
                    ->where('marketplace', $cpt->marketplace)
                    ->where('warehouse', $cpt->warehouse)
                    ->sum('discount_price');
                
                $countPriceTemp = CatalogPriceTemp::where('sku', $cpt->sku)
                    ->where('brand', $cpt->brand)
                    ->where('marketplace', $cpt->marketplace)
                    ->where('warehouse', $cpt->warehouse)
                    ->count();
                
                $countNewAvg = (($avgPriceCat * $totalDataPrice) + $totalPriceTemp) / ($totalDataPrice + $countPriceTemp);

                CatalogPriceAvg::where('sku', $cpt->sku)
                    ->where('brand', $cpt->brand)
                    ->where('marketplace', $cpt->marketplace)
                    ->where('warehouse', $cpt->warehouse)
                    ->update([
                        'average_price' => $countNewAvg,
                        'total_record' => $totalDataPrice + $countPriceTemp
                    ]);
            }

            // if($cpt->is_whitelist==false && $cpt->discount_price < $avgPriceCat){
            //     $this->errorData = $this->errorData+1;
            //     $this->dataTemp[] = array(
            //         'id' => $cpt->id,
            //         'sku' => $cpt->sku,
            //         'product_name' => $cpt->product_name,
            //         'price' => $cpt->discount_price,
            //         'discount' => $totalPriceTemp / $countPriceTemp,
            //         'average_discount' => $countNewAvg,
            //         'is_whitelist' => $cpt->is_whitelist,
            //         'is_changed' => false
            //     );
            // } 
            
            $catPrice = [
                'upload_hash' => session()->get('checkPriceHash'),
                'sku' => $cpt->sku,
                'product_name' => $cpt->product_name,
                'retail_price' => $cpt->retail_price,
                'discount_price' => $cpt->discount_price,
                'user_id' => $cpt->user_id,
                'brand' => $cpt->brand,
                'marketplace' => $cpt->marketplace,
                'is_whitelist' => $cpt->is_whitelist,
                'is_negative' => $cpt->is_negative,
                'warehouse' => $cpt->warehouse,
                'start_date' => $cpt->start_date,
            ];
            CatalogPrice::updateOrCreate(
                [
                    'upload_hash' => session()->get('checkPriceHash'),
                    'sku' => $cpt->sku,
                    'user_id' => $cpt->user_id,
                    'brand' => $cpt->brand,
                    'marketplace' => $cpt->marketplace,
                    'warehouse' => $cpt->warehouse,
                ],[
                    'discount_price' => $cpt->discount_price,
                    'product_name' => $cpt->product_name,
                    'retail_price' => $cpt->retail_price,
                    'is_whitelist' => $cpt->is_whitelist,
                    'is_negative' => $cpt->is_negative,
                    'start_date' => $cpt->start_date,
                ]
            );

        }

        // HistoryUser::create($historyData);

        // if ($this->errorData == 0) {
        //     $this->submitBtn = true;
        //     session()->flash('message', 'Data verified');
        // } else {
        //     $this->submitBtn = false;
        //     session()->flash('error', 'Please check the price again');
        // }
        $this->submitBtn = true;
        $this->beforeVerified = true;
        $this->dispatchBrowserEvent('swal:success', [
            'text' => 'All prices successfully submitted',
        ]);
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

                $averagePrice = CatalogPriceAvg::where('sku', $items->sku)->where('marketplace', $items->marketplace)->where('brand', $items->brand)->where('warehouse', $items->warehouse)->pluck('average_price')->first();

                if ($items->discount_price < $averagePrice && $items->is_discount==true) {
                    // dd($averagePrice);
                    CatalogPriceTemp::where('id',$items->id)->update(['is_negative' => true]);
                    $dataCatalog[] = array(
                        'id' => $items->id,
                        'sku' => $items->sku,
                        'product_name' => $items->product_name,
                        'price' => $items->discount_price,
                        'discount' => $avgTemp,
                        'average_discount' => $averagePrice,
                        'is_whitelist' => $items->is_whitelist,
                        'is_negative' => true,
                        'is_changed' => false
                    );

                    $extrasHistory[] = array(
                        'sku' => $items->sku,
                        'price' => $items->discount_price,
                        'average_discount' => $averagePrice
                    );
                } 
                if($items->is_whitelist){
                    $countWhitelist++;
                }
            }

            $countError = count($dataCatalog);
            // dd($updatedCatalogPriceTemp);
            $this->errorData = $countError-$countWhitelist;
            // dd($countError, $countWhitelist, $countHasDiscount);

            // dd($countError);
            $this->dataTemp = $dataCatalog ?? '';

            // Insert data to history
            $totalError = $countError;
            $insertLogUploadFile = session()->has('historyData');
            // dd(session()->all());
            if(!$insertLogUploadFile){
                $historyData = [
                    'user_id' => $userId,
                    'brand' => $brand,
                    'marketplace' => $marketplace,
                    'total_records' => $catalogTemp->count(),
                    'false_price' => $totalError,
                    'extras' => json_encode($extrasHistory)
                ];
                
                session()->put('historyData', $historyData);

            // dd($insertLogUploadFile);
                HistoryUser::create(session()->get('historyData'));
            }
            $generateHash = Str::uuid();
            session()->put('checkPriceHash', $generateHash);

            // dd(session()->all());

            $this->firstLoad = false;
            $this->brand = $brand;
            $this->marketplace = $marketplace;
        }

        return view('livewire.table.check-price');
    }
}
