<?php

namespace App\Livewire\Admin\Employee;

use App\Models\Education;
use Livewire\Component;

class EducationList extends Component
{
    public $educations;
    public $formAdd = false, $formEdit = false, $confirmingDelete = false, $selectedEducationId;
    public $name, $education_id;
    public function mount()
    {
        $this->getData();
    }
    public function getData()
    {
        $this->educations = Education::orderBy('name')->get();
    }
    public function render()
    {
        return view('livewire.admin.employee.education-list');
    }
    public function add()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:educations,name',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'name.unique' => 'Nama sudah digunakan.',
            ]);

            Education::create([
                'name' => $this->name,
            ]);

            $this->resetForm();
            $this->getData();
            $this->dispatch('success-message', 'Data berhasil ditambahkan.');

        }catch(\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $this->formEdit = true;
        $education = Education::findOrFail($id);
        $this->education_id = $education->id;
        $this->name = $education->name;
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:educations,name,' . $this->education_id,
            ], [
                'name.required' => 'Nama wajib diisi.',
                'name.unique' => 'Nama sudah digunakan.',
            ]);

            $education = Education::findOrFail($this->education_id);

            $education->update([
                'name' => $this->name,
            ]);

            $this->resetForm();
            $this->getData();
            $this->dispatch('success-message', 'Data berhasil diubah.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedEducationId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $education = Education::findOrFail($this->selectedEducationId);
            $education->delete();
            $this->getData();
            $this->confirmingDelete = false;
            $this->dispatch('success-message', 'Data berhasil dihapus.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function resetForm()
    {
        $this->formAdd = false;
        $this->formEdit = false;
        $this->education_id = '';
        $this->name = '';
    }

}
