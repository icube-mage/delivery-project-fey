<?php

namespace App\Http\Livewire\Configuration;

use App\Models\Configuration;
use App\Models\Marketplace;
use Livewire\Component;

class RowExcel extends Component
{
    protected $listeners = ['refreshConfigRow' => '$refresh'];
    public $config;
    public $templateConfig = [
        "heading"=>"",
        "content"=>"",
    ];

    public function store($configName, $index)
    {
        if($this->config[$index]['heading'] != '' && $this->config[$index]['content'] != ''){
            $this->dispatchBrowserEvent('save');
        }
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
            "heading"=>"",
            "content"=>"",
        ];
    }

    public function render()
    {
        $marketplaces = Marketplace::all();
        $maps = [];
        foreach($marketplaces as $marketplace){
            $configuration = Configuration::where('key', $marketplace->slug.'_row_map')->first();
            $getValue = $configuration->value ?? '';
            $maps[] = [
                "marketplace" => $marketplace->name,
                "config" => $configuration->key ?? $marketplace->slug.'_row_map',
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
        return view('livewire.configuration.row-excel', ['maps' => $maps]);
    }
}
