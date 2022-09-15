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
    public $titleAction = 'Create';
    protected $listeners = ['store', 'destroy'];
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

    public function alertDeleteConfirm($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'text' => 'Do you want to create this account?',
            'action' => 'delete',
            'item' => $id,
        ]);
    }

    public function store()
    {
        $data = [
            "name" => $this->name,
            "username" => $this->username,
            "email" => $this->email
        ];
        if ($this->password!=null && $this->titleAction == 'Update') {
            $data['password'] = bcrypt($this->password);
        } elseif($this->password!=null && $this->titleAction != 'Update'){
            $data['password'] = bcrypt($this->password);
        } else {
            $this->password = $this->email;
        }
        $this->validate();
        $user = User::updateOrCreate([
            'username' => $this->username
        ],$data);
        $user->assignRole($this->role);
        $this->reset();
        $this->dispatchBrowserEvent('swal:success', [
            'text' => 'Account successfully created',
        ]);
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();
        $this->dispatchBrowserEvent('swal:success', [
            'text' => 'Account successfully deleted',
        ]);
    }

    public function edit(User $user){
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->password = null;
        $this->role = $user->getRoleNames()[0];
        $this->titleAction = 'Update';
    }

    public function clear()
    {
        $this->reset();
    }
}
