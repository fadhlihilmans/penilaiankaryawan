<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;


class Login extends Component
{
    public $email, $password;
    #[Layout('components.layouts.auth')]
    public function render()
    {
        return view('livewire.admin.auth.login');
    }
    
    public function login() {
        try {
            $this->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $credentials = array(
                'email' =>  $this->email,
                'password' =>  $this->password
            );

            if (Auth::attempt($credentials)) {
                 session()->regenerate();
                 return redirect('/admin/users/list');
            }else{
                $this->dispatch('failed-message', 'Akun tidak ditemukan atau password salah');
            }

        } catch (\Throwable $th) {
            $this->dispatch('failed-message', $th->getMessage());
        }

    }

}
