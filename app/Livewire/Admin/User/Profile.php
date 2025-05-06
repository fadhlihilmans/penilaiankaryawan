<?php

namespace App\Livewire\Admin\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Profile extends Component
{
    public $user;
    public $isEmployee = false;
    public $showPasswordModal = false;
    
    public $password = '';
    public $password_confirmation = '';
    
    protected $rules = [
        'password' => ['required', 'confirmed', 'min:8'],
        'password_confirmation' => ['required']
    ];
    
    protected $messages = [
        'password.required' => 'Password tidak boleh kosong',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
        'password.min' => 'Password minimal 8 karakter',
        'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong',
    ];
    
    public function mount()
    {
        $this->getData();
    }
    
    public function getData()
    {
        $this->user = User::with(['roles', 'employees.position', 'employees.education'])
            ->where('id', Auth::user()->id)
            ->first();
            
        $this->isEmployee = $this->user->employees()->exists();
    }
    
    public function openPasswordModal()
    {
        $this->resetValidation();
        $this->password = '';
        $this->password_confirmation = '';
        $this->showPasswordModal = true;
    }
    
    public function closePasswordModal()
    {
        $this->showPasswordModal = false;
    }
    
    public function updatePassword()
    {
        $this->validate();
        
        $this->user->update([
            'password' => Hash::make($this->password),
        ]);
        
        $this->showPasswordModal = false;
        $this->dispatch('success-message', 'Password berhasil diubah.');
    }
    
    public function render()
    {
        return view('livewire.admin.user.profile');
    }
}