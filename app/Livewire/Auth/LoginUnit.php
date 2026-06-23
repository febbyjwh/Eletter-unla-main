<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginUnit extends Component
{
    public $email;
    public $password;

    public function login()
    {
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (!Auth::attempt($credentials)) {
            $this->addError('email', 'Email atau password salah.');
            return;
        }

        $user = Auth::user();

        if ($user->role_id != 3) {
            Auth::logout();
            $this->addError('email', 'Akun bukan unit.');
            return;
        }

        if ($user->status != 1) {
            Auth::logout();
            $this->addError('email', 'Unit belum aktif.');
            return;
        }

        session()->regenerate();

        return redirect('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.login-unit')
            ->layout('layouts.guest');
    }
}
