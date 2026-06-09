<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class RegisterUnit extends Component
{
    public string $nama_unit = '';

    protected $rules = [
        'nama_unit' => 'required|string|min:3|max:255',
    ];

    public function redirectToGoogle()
    {
        $this->validate();

        // Simpan ke session
        session(['pending_nama_unit' => trim($this->nama_unit)]);
        session()->save(); 

        return redirect()->route('google.unit');
    }

    public function render()
    {
        return view('livewire.auth.register-unit')
            ->layout('layouts.guest');
    }
}
