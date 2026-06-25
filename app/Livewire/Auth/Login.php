<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;

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

        if ($user->role_id == 3) {

            Auth::logout();

            $this->addError(
                'email',
                'Silakan login melalui halaman unit.'
            );

            return;
        }

        if ($user->status != 1) {

            Auth::logout();

            $this->addError(
                'email',
                'Akun Anda belum diaktifkan oleh administrator. Silakan menunggu persetujuan atau hubungi admin.'
            );

            return;
        }
    
        session()->regenerate();

        return redirect('/dashboard');
    }

    public function render()
    {
        return view(
            'livewire.auth.login'
        )->layout('layouts.guest');
    }
}
