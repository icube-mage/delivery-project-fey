<?php

namespace App\Http\Livewire\Menu;

use App\Models\Brand;
use Livewire\Component;
use App\Models\Marketplace;
use Livewire\WithFileUploads;
use App\Imports\FileDataImport;
use Illuminate\Support\Facades\Auth;

class UploadFile extends Component
{
    use WithFileUploads;
    
    public $title = 'Upload File';
    public $userId;
    public $brand;
    public $marketplace;
    public $file;

    public function render()
    {
        $brands = Brand::all();
        $marketplaces = Marketplace::all();
        return view('livewire.menu.upload-file',['brands' => $brands, 'marketplaces' => $marketplaces]);
    }

    public function submit()
    {
        // dd("halo");
        Excel::import(new FileDataImport, $this->file);
    }

    public function test(){
        dd("halo");
    }

    public function userId()
    {
        $this->userId = Auth::user()->id;
    }

    public function brand()
    {
        $this->brand;
    }

    public function marketplace()
    {
        $this->brand;
    }
}
