<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class LoginUnit extends Component
{
    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|string|min:8',
    ];

    public function login()
    {
        if (!Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ])) {

            $this->addError(
                'email',
                'Email atau password salah.'
            );

            return;
        }

        $user = Auth::user();

        if ($user->role_id != 3) {

            Auth::logout();

            $this->addError(
                'email',
                'Akun ini bukan akun unit.'
            );

            return;
        }

        if ($user->status != 1) {

            Auth::logout();

            $this->addError(
                'email',
                'Unit masih menunggu approval admin.'
            );

            return;
        }

        session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.login-unit')->layout('layouts.guest');
    }
}
