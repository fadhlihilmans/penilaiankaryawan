<?php

namespace App\Livewire\Admin\Employee;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Position;
use App\Models\Education;
use App\Models\Employee;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class EmployeeList extends Component
{
    use WithFileUploads;

    public $employees, $positions, $educations, $selectedEmployee;
    public $name, $email, $image, $position_id, $education_id, $user_id, $nip, $birth_place, $birth_date, $gender, $employment_status, $oldImage;
    public $formAdd = false, $formEdit = false;
    public $confirmingDelete = false;
    // public $confirmingResetPassword = false;
    public $detailOpen = false;
    public $selectedEmployeeId;
    public $employeeId, $userId;

    public function mount()
    {
        $this->getData();
    }
    public function getData()
    {
        $this->employees = Employee::with('user', 'position', 'education')->latest()->get();
        $this->positions = Position::orderBy('name')->get();
        $this->educations = Education::orderBy('name')->get();
    }
    public function render()
    {
        return view('livewire.admin.employee.employee-list');
    }
    public function add()
    {
        try {
            DB::beginTransaction();

            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'image' => 'nullable|image',
                'position_id' => 'required',
                'education_id' => 'required',
                'nip' => 'required',
                'birth_place' => 'required',
                'birth_date' => 'required',
                'gender' => 'required',
                'employment_status' => 'required',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan.',
                'image.image' => 'File harus berupa gambar.',
                'position_id.required' => 'Jabatan harus dipilih.',
                'education_id.required' => 'Pendidikan harus dipilih.',
                'nip.required' => 'NIP wajib diisi.',
                'birth_place.required' => 'Tempat lahir wajib diisi.',
                'birth_date.required' => 'Tanggal lahir wajib diisi.',
                'employment_status.required' => 'Status wajib diisi.',
            ]);

            $manager = ImageManager::withDriver(GdDriver::class);

            $image = $manager->read($this->image->getRealPath())
                ->cover(300, 300)
                ->toWebp(90);

            $imgName = 'user_' . Str::random(10) . '.webp';
            $path = public_path('images/employee/' . $imgName);
            file_put_contents($path, (string) $image);

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make('12345678'),
            ]);

            UserRole::create([
                'user_id' => $user->id,
                'role_id' => 3,
            ]);

            Employee::create([
                'user_id' => $user->id,
                'position_id' => $this->position_id,
                'education_id' => $this->education_id,
                'nip' => $this->nip,
                'birth_place' => $this->birth_place,
                'birth_date' => $this->birth_date,
                'gender' => $this->gender,
                'employment_status' => $this->employment_status,
                'image' => $imgName,
            ]);

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
        $employee = Employee::with('user', 'position', 'education')->findOrFail($id);
        $this->employeeId = $employee->id;
        $this->userId = $employee->user->id;
        $this->name = $employee->user->name;
        $this->email = $employee->user->email;
        $this->position_id = $employee->position_id;
        $this->oldImage = $employee->image;
        $this->nip = $employee->nip;
        $this->birth_place = $employee->birth_place;
        $this->birth_date = $employee->birth_date;
        $this->gender = $employee->gender;
        $this->employment_status = $employee->employment_status;
        $this->education_id = $employee->education_id;
    }

    public function update()
    {
        try {
            DB::beginTransaction();

            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $this->userId,
                'image' => 'nullable|image',
                'position_id' => 'required',
                'education_id' => 'required',
                'nip' => 'required',
                'birth_place' => 'required',
                'birth_date' => 'required',
                'gender' => 'required',
                'employment_status' => 'required',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan.',
                'image.image' => 'File harus berupa gambar.',
                'position_id.required' => 'Jabatan harus dipilih.',
                'education_id.required' => 'Pendidikan harus dipilih.',
                'nip.required' => 'NIP wajib diisi.',
                'birth_place.required' => 'Tempat lahir wajib diisi.',
                'birth_date.required' => 'Tanggal lahir wajib diisi.',
                'employment_status.required' => 'Status wajib diisi.',
            ]);

            $employee = Employee::findOrFail($this->employeeId);

            if ($this->image) {
                if ($employee->image && file_exists(public_path('images/employee/' . $employee->image))) {
                    unlink(public_path('images/employee/' . $employee->image));
                }
            
                $manager = ImageManager::withDriver(GdDriver::class);
            
                $image = $manager->read($this->image->getRealPath())
                    ->cover(300, 300)
                    ->toWebp(90);
            
                $imgName = 'employe_' . Str::random(10) . '.webp';
                $path = public_path('images/employee/' . $imgName);
                file_put_contents($path, (string) $image);

                $employee->update([
                    'image' => $imgName,
                ]);
            }

            $employee->user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);     

            $employee->update([
                'position_id' => $this->position_id,
                'nip' => $this->nip,
                'birth_place' => $this->birth_place,
                'birth_date' => $this->birth_date,
                'gender' => $this->gender,
                'employment_status' => $this->employment_status,
                'education_id' => $this->education_id,
            ]);

            DB::commit();

            $this->resetForm();
            $this->getData();
            $this->dispatch('success-message', 'User berhasil diupdate.');

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedEmployeeId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $employee = Employee::findOrFail($this->selectedEmployeeId);

            if ($employee->image) {
                $imgPath = public_path('images/employee/' . $employee->image);
                if (file_exists($imgPath)) {
                    unlink($imgPath);
                }
            }

            $employee->delete();
            $this->getData();
            $this->confirmingDelete = false;
            $this->dispatch('success-message', 'User berhasil dihapus.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function detail($id)
    {
        $this->selectedEmployee = Employee::with('user', 'position', 'education')->findOrFail($id);
        $this->detailOpen = true;
    }
    
    public function resetForm()
    {
        $this->formAdd = false;
        $this->formEdit = false;
        $this->detailOpen = false;
        $this->employeeId = null;
        $this->name = '';
        $this->email = '';
        $this->position_id = '';
        $this->birth_place = '';
        $this->birth_date = '';
        $this->gender = '';
        $this->employment_status = '';
        $this->education_id = '';
        $this->nip = '';
        $this->image = null;
        $this->oldImage = null;
    }

}