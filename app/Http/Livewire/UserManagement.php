<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use WithPagination;
    public $searchTerm;
    public $currentPage = 1;
    public $canSubmit = false;
    public $name, $email, $username, $password, $role;
    public $titleAction = 'Create';
    public $showModal = false;
    protected $listeners = ['store', 'destroy', 'closeModal'];
    protected function rules()
    {
        $validator =  [
            'name' => 'required|min:3',
            'role' => 'required|in:"Store Operations","Key Account Manager","Super Admin"',
        ];
        if($this->titleAction == 'Create') {
            $validator['password'] = 'required|min:6';
            $validator['email'] = 'required|email:rfc,dns|unique:users,email,'.$this->email;
            $validator['username'] = 'required|min:4|unique:users,username,'.$this->username;
        } else {
            $validator['email'] = 'required|email:rfc,dns';
            $validator['username'] = 'required|min:4';
        }
        return $validator;
    } 

    public function updatedName()
    {
        $this->validateOnly('name', ['name' => 'required|min:3']);  
    }

    public function updatedRole()
    {
        $this->validateOnly('role', ['role' => 'required|in:"Store Operations","Key Account Manager","Super Admin"']);
    }

    public function updatedPassword()
    {
        if ($this->titleAction == 'Create') {
            $this->validateOnly('password', ['password' => 'required|min:6']);
        }
    }

    public function updatedUsername()
    {
        if ($this->titleAction == 'Create') {
            $this->validateOnly('username', ['username' => 'required|min:4|unique:users,username,'.$this->username]);
        } else {
            $this->validateOnly('username', ['username' => 'required|min:6']);
        }
    }

    public function updatedEmail()
    {
        if ($this->titleAction == 'Create') {
            $this->validateOnly('email', ['email' => 'required|email:rfc,dns|unique:users,email,'.$this->email]);
        } else {
            $this->validateOnly('email', ['email' => 'required|email:rfc,dns']);
        }
    }

    public function render()
    {
        $query = '%'.$this->searchTerm.'%';
        $users = User::where(function($sub_query) use($query){
            $sub_query->where('name', 'like', $query)
                    ->orWhere('username', 'like', $query)
                    ->orWhere('email', 'like', $query);
        })->paginate(10);

        if(Auth::user()->hasRole('Super Admin')){
            $roles = Role::all();
        } elseif(Auth::user()->hasRole('Key Account Manager')){
            $roles = Role::where('name', '<>', 'Super Admin')->get();
        } else {
            $roles = [];
        } 

        if(count($this->getErrorBag()->messages())==0 && $this->role != ''){
            $this->canSubmit = true;
        } else {
            $this->canSubmit = false;
        }
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
        if ($this->titleAction == 'Create') {
            $wording = 'Do you want to create this account?';
        } else {
            $wording = 'Do you want to update this account?';
        }
        $this->dispatchBrowserEvent('swal:confirm', [
            'text' => $wording,
        ]);
    }

    public function alertDeleteConfirm($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'text' => 'Do you want to delete this account?',
            'action' => 'delete',
            'item' => $id,
        ]);
    }

    public function closeModal(){
        $this->showModal = false;
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
        $user->syncRoles($this->role);
        if ($this->titleAction == 'Create') {
            $wording = 'Account successfully created';
        } else {
            $wording = 'Account successfully updated';
        }
        $this->reset();
        $this->dispatchBrowserEvent('swal:success', [
            'text' => $wording,
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
        $this->resetValidation();
        $this->titleAction = 'Update';
    }

    public function clearForm()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function hideModal()
    {
        $this->resetValidation();
    }
}
