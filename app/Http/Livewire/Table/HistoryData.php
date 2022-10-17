<?php

namespace App\Http\Livewire\Table;

use App\Models\CatalogPrice;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class HistoryData extends Component
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
        $input = '%'.$this->searchTerm.'%';
        $catalogPrices = CatalogPrice::with('user')
        ->select(['upload_hash', 'brand', 'marketplace','start_date','users.name'])
        ->leftJoin('users', 'catalog_prices.user_id', '=', 'users.id')
        ->where('user_id', auth()->user()->id)
        ->where(function($sub_query) use($input){
            $sub_query->where('brand', 'like', $input)
                ->orWhere('marketplace', 'like', $input)
                ->orWhere('upload_hash', 'like', $input)
                ->orWhereHas('user', function ($sub_query) use ($input) {
                    $sub_query->where('name', 'like', $input);
                });
        })
        ->groupBy(['upload_hash', 'brand', 'marketplace','start_date', 'users.name'])
        ->paginate(10);
        return view('livewire.table.history-data', compact('catalogPrices'));
    }
}
