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
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ])) {
            $user = Auth::user();

            if ($user->status == 0) {
                Auth::logout();
                $this->addError('email', 'Akun Anda belum aktif.');
                return;
            }

            session()->regenerate();
            return redirect()->intended('/dashboard');
        }
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}
