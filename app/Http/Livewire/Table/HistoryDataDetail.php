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
        ->where('name', 'like', $input)
        ->paginate(10);
        return view('livewire.table.history-data-detail', compact('catalogPrices'));
    }
}
