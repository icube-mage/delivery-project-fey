<?php

namespace App\Http\Livewire\Table;

use App\Models\User;
use Livewire\Component;

class ReportUser extends Component
{
    public $searchTerm;

    public function render()
    {
        $input = '%'.$this->searchTerm.'%';
        $users = User::with(['uploadHistory', 'roles'])
        ->where(function($query) use($input){
            $query->where('name', 'like', $input)
                ->orWhereHas('uploadHistory', function ($sub_query) use ($input) {
                    $sub_query->where('brand', 'like', $input)
                        ->orWhere('marketplace', 'like', $input);
                });
        })
        ->whereHas('roles', function($query){
            $query->where('name', 'Store Operations');
        })->get();
        return view('livewire.table.report-user', compact('users'));
    }
}
