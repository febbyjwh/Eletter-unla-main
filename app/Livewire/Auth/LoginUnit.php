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
        if (Auth::guard('unit')->attempt([
            'email' => $this->email,
            'password' => $this->password,
        ])) {

            $unit = Auth::guard('unit')->user();

            if ($unit->status == 0) {
                Auth::guard('unit')->logout();

                $this->addError('email', 'Unit belum aktif.');
                return;
            }

            session()->regenerate();

            return redirect('/dashboard-unit');
        }

        $this->addError('email', 'Email atau password salah.');
    }

    public function render()
    {
        return view('livewire.auth.login-unit')->layout('layouts.guest');
    }
}
