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
    public $checkAll = false;

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

    public function selectAllWhitelist($value){
        foreach($this->dataTemp as $key => $item){
            if($value){
                $this->dataTemp[$key]['is_whitelist'] = $value;
                CatalogPriceTemp::where('id',$this->dataTemp[$key])->update(['is_whitelist' => $value]);
            } else{
                $this->dataTemp[$key]['is_whitelist'] = $value;
                CatalogPriceTemp::where('id',$this->dataTemp[$key])->update(['is_whitelist' => $value]);
            }
        }
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
            $this->checkAll = false;
        } else {
            $this->errorData = $this->errorData-1;
            $countWhitelist = array_count_values(array_map(function($val) {return $val ? 'true':'false';},array_column($this->dataTemp, 'is_whitelist')))['true'];
            if($countWhitelist == count($this->dataTemp)){
                $this->checkAll = true;
            }

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
        
        CatalogPriceTemp::where('user_id', Auth::user()->id)->where('brand', $this->brand)->where('marketplace', $this->marketplace)
        ->chunk(100, function ($dataCatalogPriceTemp) {
            $preCatalogPrices = [];
            foreach ($dataCatalogPriceTemp as $cpt) {
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
            CatalogPrice::insert($preCatalogPrices);
        });
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
