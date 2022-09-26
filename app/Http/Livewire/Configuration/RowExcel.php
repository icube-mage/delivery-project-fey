<?php

namespace App\Http\Livewire\Configuration;

use App\Models\Configuration;
use App\Models\Marketplace;
use Livewire\Component;

class RowExcel extends Component
{
    public $config = [];

    public function store($key, $index)
    {
        if($this->config[$index] == '')
        {
            Configuration::where('key', $key)->delete();
        } else {
            Configuration::updateOrCreate([
                'key' => $key
            ],[
                'key' => $key,
                'value' => $this->config[$index]
            ]);
        }
    }

    public function render()
    {
        $marketplaces = Marketplace::all();
        $maps = [];
        foreach($marketplaces as $marketplace){
            $configuration = Configuration::where('key', $marketplace->slug.'_row_map')->first();
            $maps[] = [
                "marketplace" => $marketplace->name,
                "config" => $configuration->key ?? $marketplace->slug.'_row_map',
                "value" => $configuration->value ?? '',
            ];
            $this->config[] = $configuration->value ?? '';
        }
        return view('livewire.configuration.row-excel', ['maps' => $maps]);
    }
}
