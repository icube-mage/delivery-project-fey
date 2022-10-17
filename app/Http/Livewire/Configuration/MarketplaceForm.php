<?php

namespace App\Http\Livewire\Configuration;

use App\Models\Marketplace;
use Livewire\Component;
use Illuminate\Support\Str;

class MarketplaceForm extends Component
{
    public $marketplace;

    public function store()
    {
        $this->validate([
            'marketplace' => 'required',
        ]);
        Marketplace::create(['name' => $this->marketplace, 'slug' => Str::slug($this->marketplace)]);
        $this->reset();
        $this->emitTo('configuration.mapping-excel', 'refreshConfigColumn');
        $this->emitTo('configuration.row-excel', 'refreshConfigRow');
    }
    public function render()
    {
        $marketplaces = Marketplace::all();
        return view('livewire.configuration.marketplace-form', ['marketplaces' => $marketplaces]);
    }
    public function destroy($id)
    {
        Marketplace::find($id)->delete();
        $this->emitTo('configuration.mapping-excel', 'refreshConfigColumn');
        $this->emitTo('configuration.row-excel', 'refreshConfigRow');
    }
}
