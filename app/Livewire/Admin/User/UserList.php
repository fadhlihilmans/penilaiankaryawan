<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class UserList extends Component
{
    use WithFileUploads;

    public $users, $roles;
    public $userId;
    public $name, $email, $roleIds = [];
    public $formAdd = false, $formEdit = false;
    public $confirmingDelete = false;
    public $confirmingResetPassword = false;
    public $selectedUserId;


    public function mount()
    {
        $this->getData();
    }

    public function getData()
    {
        $this->users = User::with('roles')->get();
        $this->roles = Role::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.admin.user.user-list');
    }

    public function addUser()
    {
        try {
            DB::beginTransaction();

            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'roleIds' => 'required|array|min:1',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan.',
                'roleIds.required' => 'Role harus dipilih.',
            ]);

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make('12345678'),
            ]);

            foreach ($this->roleIds as $roleId) {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $roleId,
                ]);
            }

            DB::commit();
            $this->resetForm();
            $this->getData();
            $this->dispatch('success-message', 'User berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $this->formEdit = true;
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->roleIds = $user->roles->pluck('id')->toArray();
    }

    public function updateUser()
    {
        try {
            DB::beginTransaction();

            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $this->userId,
                'roleIds' => 'required|array|min:1',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan.',
                'roleIds.required' => 'Role harus dipilih.',
            ]);

            $user = User::findOrFail($this->userId);

            $user->name = $this->name;
            $user->email = $this->email;
            $user->save();

            UserRole::where('user_id', $user->id)->delete();
            foreach ($this->roleIds as $roleId) {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $roleId,
                ]);
            }

            DB::commit();
            $this->resetForm();
            $this->getData();
            $this->dispatch('success-message', 'User berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function resetForm()
    {
        $this->formAdd = false;
        $this->formEdit = false;
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->roleIds = [];
    }

    public function confirmDelete($id)
    {
        $this->selectedUserId = $id;
        $this->confirmingDelete = true;
    }

    public function confirmResetPassword()
    {
        $this->confirmingResetPassword = true;
    }

    public function deleteConfirmed()
    {
        try {
            $user = User::findOrFail($this->selectedUserId);

            $user->delete();
            $this->getData();
            $this->confirmingDelete = false;
            $this->dispatch('success-message', 'User berhasil dihapus.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function resetPasswordConfirmed()
    {
        try {
            $user = User::findOrFail($this->userId);
            $user->password = Hash::make('12345678');
            $user->save();

            $this->confirmingResetPassword = false;
            $this->dispatch('success-message', 'Password berhasil direset.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }


}
