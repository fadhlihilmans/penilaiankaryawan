<?php

namespace App\Livewire\Admin\Employee;

use App\Models\Education;
use Livewire\Component;
use Livewire\WithPagination;

class EducationList extends Component
{
    use WithPagination;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false, $selectedEducationId;
    public $name, $education_id;
    public $search = '';
    
    public function updatedSearch()
    {
        $this->resetPage(); 
    }
    public function render()
    {
        $educations = Education::search($this->search)->orderBy('name')->paginate(10);
        return view('livewire.admin.employee.education-list', compact('educations'));
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
            $this->dispatch('datatable:refresh');
            session()->flash('success-message', 'Data berhasil ditambahkan.');
            return redirect()->route('admin.pendidikan.list');

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
            $this->dispatch('datatable:refresh');
            session()->flash('success-message', 'Data berhasil diubah.');
            return redirect()->route('admin.pendidikan.list');
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
            $this->dispatch('datatable:refresh');
            $this->confirmingDelete = false;
            session()->flash('success-message', 'Data berhasil dihapus.');
            return redirect()->route('admin.pendidikan.list');
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
