<?php

namespace App\Http\Livewire\Configuration;

use App\Models\Configuration;
use App\Models\Marketplace;
use Livewire\Component;

class MappingExcel extends Component
{
    protected $listeners = ['refreshConfigColumn' => '$refresh'];
    public $config;
    public $templateConfig = [
        "sku"=>"",
        "product_name"=>"",
        "discount_price"=>"",
        "warehouse"=>"",
        "retail_price"=>"",
        "start_date"=>"",
    ];

    public function store($configName, $index)
    {
        $arrayValue = [];
        foreach($this->config[$index] as $key => $value ){
            if($value!=''){
                $arrayValue[] = $key."=".$value;
            }
        }
        $stringValue = implode(",", $arrayValue);

        if($this->config[$index] == '')
        {
            Configuration::where('key', $configName)->delete();
        } else {
            Configuration::updateOrCreate([
                'key' => $configName
            ],[
                'key' => $configName,
                'value' => $stringValue
            ]);
        }
        $this->dispatchBrowserEvent('refresh');
    }

    public function clearConfig($configName, $index){
        Configuration::where('key', $configName)->delete();
        $this->config[$index] = [
            "sku"=>"",
            "product_name"=>"",
            "discount_price"=>"",
            "warehouse"=>"",
            "retail_price"=>"",
            "start_date"=>"",
        ];
    }

    public function render()
    {
        $marketplaces = Marketplace::all();
        $maps = [];
        foreach($marketplaces as $marketplace){
            $configuration = Configuration::where('key', $marketplace->slug.'_column_map')->first();
            $getValue = $configuration->value ?? '';
            $maps[] = [
                "marketplace" => $marketplace->name,
                "config" => $configuration->key ?? $marketplace->slug.'_column_map',
            ];
            $basicConfig = $this->templateConfig;
            if ($getValue) {
                $getConfig = explode(",", $configuration->value);
                foreach ($getConfig as $array) {
                    $value = explode("=", $array);
                    $basicConfig[$value[0]] = $value[1];
                }
            }
            $this->config[] = $basicConfig;
        }
        return view('livewire.configuration.mapping-excel', ['maps' => $maps]);
    }
}
