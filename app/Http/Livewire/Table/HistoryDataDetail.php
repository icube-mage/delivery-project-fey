<?php

namespace App\Http\Livewire\Table;

use App\Models\CatalogPrice;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class HistoryDataDetail extends Component
{
    use WithPagination;
    public $searchTerm;
    public $currentPage = 1;
    public $hash;
    public $errorData = false;
    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function(){
            return $this->currentPage;
        });
    }
    public function render()
    {
        $input = '%'.$this->searchTerm.'%';
        $catalogPrices = CatalogPrice::with('user')
        ->where('upload_hash', $this->hash)
        ->where(function($query) use($input){
            $query->where('product_name', 'like', $input)
            ->orWhere('sku', 'like', $input);
        })
        ->paginate(10);
        $this->errorData = false;
        if($catalogPrices->count()==0){
            $catalogPrices = CatalogPrice::with('user')->where('upload_hash', $this->hash)->paginate(1);
            $this->errorData = true;
        }
        return view('livewire.table.history-data-detail', compact('catalogPrices'));
    }
}
