<?php

namespace App\Livewire\Admin\Employee;

use App\Models\Position;
use Livewire\Component;

class PositionList extends Component
{
    public $positions;
    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $postion_id, $name, $description;
    public $selectedPositionId;
    public function mount()
    {
        $this->getData();
    }
    public function getData()
    {
        $this->positions = Position::orderBy('name')->get();
    }
    public function render()
    {
        return view('livewire.admin.employee.position-list');
    }
    public function add()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:positions,name',
                'description' => 'required',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'name.unique' => 'Nama sudah digunakan.',
                'description.required' => 'Deskripsi wajib diisi.',
            ]);

            Position::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            $this->resetForm();
            $this->getData();
            $this->dispatch('success-message', 'Data berhasil ditambahkan.');

        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $this->formEdit = true;
        $position = Position::findOrFail($id);
        $this->postion_id = $position->id;
        $this->name = $position->name;
        $this->description = $position->description;   
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:positions,name,' . $this->postion_id,
                'description' => 'required',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'name.unique' => 'Nama sudah digunakan.',
                'description.required' => 'Deskripsi wajib diisi.',
            ]);

            $position = Position::findOrFail($this->postion_id);

            $position->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            $this->reset();
            $this->getData();
            $this->dispatch('success-message', 'Data berhasil diubah.');

        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }
    public function confirmDelete($id)
    {
        $this->selectedPositionId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $position = Position::findOrFail($this->selectedPositionId);
            $position->delete();
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
        $this->postion_id = '';
        $this->name = '';
        $this->description = '';
    }
    
}
