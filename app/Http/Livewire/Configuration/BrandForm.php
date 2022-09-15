<?php

namespace App\Http\Livewire\Configuration;

use App\Models\Brand;
use Livewire\Component;
use Illuminate\Support\Str;

class BrandForm extends Component
{
    public $merchant;

    public function store()
    {
        $this->validate([
            'merchant' => 'required',
        ]);
        Brand::create(['name' => $this->merchant, 'slug' => Str::slug($this->merchant)]);
        $this->reset();
    }
    public function render()
    {
        $brands = Brand::all();
        return view('livewire.configuration.brand-form', ['brands' => $brands]);
    }
    public function destroy($id)
    {
        Brand::find($id)->delete();
    }
}
