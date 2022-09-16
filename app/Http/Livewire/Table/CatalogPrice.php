<?php

namespace App\Http\Livewire\Table;

use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class CatalogPrice extends Component
{
    use WithPagination;
    public $searchTerm;
    public $currentPage = 1;
    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function(){
            return $this->currentPage;
        });
    }
    public function render()
    {
        $query = '%'.$this->searchTerm.'%';
        $catalogPrices = CatalogPrice::where(function($sub_query) use($query){
            $sub_query->where('brand', 'like', $query)
                    ->orWhere('marketplace', 'like', $query);
        })->whereHas('users', function ($q) use ($query) {
            $q->where('name', 'like', $query);
        })->paginate(10);

        dd($catalogPrices);
        return view('livewire.table.catalog-price', compact('catalogPrices'));
    }
}
