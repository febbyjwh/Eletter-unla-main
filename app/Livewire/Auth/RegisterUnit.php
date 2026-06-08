<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class RegisterUnit extends Component
{
    public $email;
    public $nama_unit;
    public $password;

    protected $rules = [
        'email' => 'required|email|unique:users,email',
        'nama_unit' => 'required|string|max:255',
        'password' => 'required|string|min:8',
    ];

    public function register()
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {

            $unit = Unit::create([
                'kode_unit' => 'UNIT-' . strtoupper(Str::random(6)),
                'nama_unit' => $validated['nama_unit'],
                'status' => 0,
            ]);

            User::create([
                'name' => $validated['nama_unit'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => 3,
                'unit_id' => $unit->id,
                'status' => 0,
            ]);
        });

        session()->flash(
            'success',
            'Pendaftaran unit berhasil. Menunggu approval admin.'
        );
    }

    public function render()
    {
        return view('livewire.auth.register-unit')
            ->layout('layouts.guest');
    }
}
