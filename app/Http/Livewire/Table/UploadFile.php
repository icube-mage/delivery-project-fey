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
    public $isUploaded;

    protected $listeners = ['setUploaded'];
    
    public function mount($isUploaded){
        $this->isUploaded  = $isUploaded;
    }

    public function setUploaded($boolean){
        $this->isUploaded = $boolean;
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

    public function render()
    {
        if($this->isUploaded == false){
            $catalogPriceTemp = [];
        } else{
            $catalogPriceTemp = CatalogPriceTemp::orderBy($this->sortField, $this->sortDirection)->paginate(10);
        }

        return view('livewire.table.upload-file',['catalogPriceTemp' => $catalogPriceTemp]);
    }
}
