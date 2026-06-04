<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterUnit extends Component
{
    public $email;
    public $nama_unit;
    public $password;

    protected $rules = [
        'email' => 'required|email|unique:unit,email',
        'nama_unit' => 'required|string|max:255',
        'password' => 'required|string|min:8',
    ];

    public function register()
    {
        $validated = $this->validate();

        try {
            Unit::create([
                'kode_unit' => 'UNIT-' . strtoupper(Str::random(6)),
                'email' => $validated['email'],
                'nama_unit' => $validated['nama_unit'],
                'password' => Hash::make($validated['password']),
                'status' => 0,
            ]);

            $this->reset(['email', 'nama_unit', 'password']);

            session()->flash('success', 'Unit berhasil didaftarkan!');
        } catch (\Exception $e) {
            logger()->error($e->getMessage());

            session()->flash('error', 'Terjadi kesalahan saat mendaftarkan unit.');
        }
    }

    public function render()
    {
        return view('livewire.auth.register-unit')
            ->layout('layouts.guest');
    }
}
