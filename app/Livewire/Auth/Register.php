<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Register extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public $showPassword = false;
    public $showConfirmPassword = false;

    public function registerManual()
    {
        $this->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|same:password_confirmation',
        ]);

        User::create([
            'name' => explode('@', $this->email)[0],
            'email' => $this->email,
            'password' => bcrypt($this->password),

            'role_id' => 2, // user
            'status' => 0,  // pending
        ]);

        session()->flash(
            'success',
            'Registrasi berhasil. Silahkan tunggul approval adrimistrator.'
        );

        $this->reset();
    }

    public function redirectToGoogle()
    {
        return redirect()->route('google.register');
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function toggleConfirmPassword()
    {
        $this->showConfirmPassword = !$this->showConfirmPassword;
    }

    public function register()
    {
        // validasi input
        $validated = Validator::make([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ])->validate();

        // buat user baru
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => 2,
            'status' => 0,
        ]);

        session()->flash('success', 'Akun berhasil dibuat. Tunggu persetujuan admin.');

        $this->reset(['name', 'email', 'password', 'password_confirmation']);
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.guest');
    }
}
