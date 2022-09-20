<?php

namespace App\Http\Livewire\Table;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CatalogPriceTemp;
use Illuminate\Pagination\Paginator;

class UploadFile extends Component
{
    use WithPagination;

    public $sortField = 'id';
    public $sortDirection = 'asc';

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

    public function render()
    {
        return view('livewire.table.upload-file',['catalogPriceTemp' => CatalogPriceTemp::orderBy($this->sortField, $this->sortDirection)->paginate(10)]);
    }
}
