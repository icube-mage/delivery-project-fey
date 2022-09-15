<?php

namespace App\Http\Livewire\Configuration;

use App\Models\Configuration;
use Livewire\Component;

class Form extends Component
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
        return view('livewire.configuration.form');
    }
    public function updatedCsvSeparator()
    {
        Configuration::where('key', 'csv_field_separator')->update([
            'key' => $this->csv_separator
        ]);
        session()->flash('success', 'Value has been changed');
    }
    public function updatedTimeCalculate()
    {
        Configuration::where('key', 'average_max_time_calculate')->update([
            'key' => $this->time_calculate
        ]);
        session()->flash('success', 'Value has been changed');
    }
    public function updatedCronSchedule()
    {
        Configuration::where('key', 'crontab_schedule_running')->update([
            'key' => $this->cron_schedule
        ]);
        session()->flash('success', 'Value has been changed');
    }
}
