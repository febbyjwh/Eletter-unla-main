<?php

namespace App\Livewire\Auth;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Register extends Component
{
    public $email;
    public $password;
    public $role;

    public $roles = [];

    public function mount()
    {
        $this->roles = Role::whereIn('id', [2, 3, 4])->get();
    }

    // public function register()
    // {
    //     $roles   = Role::all();
    //     return view("livewire.auth.register", compact('roles'))->layout('layouts.guest');
    // }

    public function submitRegister()
    {
        if (!$this->role) {
            $this->addError('role', 'Pilih role dulu');
            return;
        }

        return redirect("/auth/google/register/{$this->role}");
    }

    

    public function render()
    {
        return view(
            'livewire.auth.register',
            [
                'roles' => $this->roles,
            ]
        )->layout('layouts.guest');
    }
}
