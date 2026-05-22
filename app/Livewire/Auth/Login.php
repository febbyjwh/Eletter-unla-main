<?php

namespace App\Livewire\Auth;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public $role;

    public $roles = [];

    public function mount()
    {
        $this->roles = Role::whereIn('id', [2, 3])->get();
    }

    // public function register()
    // {
    //     dd($this->role);
    // }

    public function register()
    {
        $roles   = Role::all();
        return view("livewire.auth.register", compact('roles'))->layout('layouts.guest');
    }

    public function sumbitRegister()
    {
        if (!$this->role) {
            $this->addError('role', 'Silakan pilih role terlebih dahulu.');
            return;
        }

        session(['selected_role' => $this->role]);

        return redirect()->route('google.register');
    }

    public function login()
    {
        if (Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ])) {

            $user = Auth::user();

            if ($user->status == 0) {
                Auth::logout();

                $this->addError(
                    'email',
                    'Akun Anda belum aktif.'
                );

                return;
            }

            session()->regenerate();

            return redirect()
                ->intended('/dashboard');
        }

        $this->addError(
            'email',
            'Email atau password salah.'
        );
    }

    public function render()
    {
        return view(
            'livewire.auth.login'
        )->layout('layouts.guest');
    }
}
