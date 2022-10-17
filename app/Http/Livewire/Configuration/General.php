<?php

namespace App\Http\Livewire\Configuration;

use App\Models\Configuration;
use Livewire\Component;

class General extends Component
{
    public $csv_separator;
    public $time_calculate;
    public $cron_schedule;
    // public $image_placeholder;
    public function mount()
    {
        $this->csv_separator = Configuration::where('key', 'csv_field_separator')->first()->value;
        $this->time_calculate = Configuration::where('key', 'average_max_time_calculate')->first()->value;
        $this->cron_schedule = Configuration::where('key', 'crontab_schedule_running')->first()->value;
        // $this->image_placeholder = Configuration::where('key', 'logo_image_placeholder')->first()->value;
    }
    public function render()
    {
        return view('livewire.configuration.general');
    }
    public function store()
    {
        Configuration::where('key', 'csv_field_separator')->update([
            'value' => $this->csv_separator
        ]);
        Configuration::where('key', 'average_max_time_calculate')->update([
            'value' => $this->time_calculate
        ]);
        Configuration::where('key', 'crontab_schedule_running')->update([
            'value' => $this->cron_schedule
        ]);
        $this->dispatchBrowserEvent('refresh');
        session()->flash('success', 'Value has been changed');
    }
}
