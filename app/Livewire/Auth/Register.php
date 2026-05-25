<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Register extends Component
{
     public function register()
    {
        return redirect()->route('google.register');
    }

    public function render()
    {
        return view(
            'livewire.auth.register'
        )->layout('layouts.guest');
    }
}