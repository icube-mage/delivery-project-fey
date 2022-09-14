<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use WithPagination;
    public $searchTerm;
    public $currentPage = 1;
    public $name, $email, $username, $password;
    public $role;
    protected $listeners = ['store'];
    protected $rules = [
        'name' => 'required',
        'username' => 'required|min:6',
        'email' => 'required|email',
        'password' => 'required|min:4',
        'role' => 'required'
    ];

    public function render()
    {
        $query = '%'.$this->searchTerm.'%';
        
        $users = User::where(function($sub_query) use($query){
            $sub_query->where('name', 'like', $query)
                    ->orWhere('username', 'like', $query)
                    ->orWhere('email', 'like', $query);
        })->paginate(10);
        $roles = Role::all();
        return view('livewire.user-management', ["users" => $users, "roles" => $roles])
            ->layout('layouts.app', ['title'=>"User Management"]);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function(){
            return $this->currentPage;
        });
    }

    public function alertConfirm()
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'text' => 'Do you want to create this account?',
        ]);
    }

    public function store()
    {
        $this->validate();
        $user = User::create([
            "name" => $this->name,
            "username" => $this->username,
            "email" => $this->email,
            "password" => bcrypt($this->password),
        ]);
        $user->assignRole($this->role);
        $this->dispatchBrowserEvent('swal:success', [
            'text' => 'Account successfully created',
        ]);
    }
}
