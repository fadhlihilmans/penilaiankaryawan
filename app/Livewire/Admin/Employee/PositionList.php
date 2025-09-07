<?php

namespace App\Livewire\Admin\Employee;

use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;


class PositionList extends Component
{
    use WithPagination;
    
    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $postion_id, $name, $description;
    public $selectedPositionId;
    public $search = "";
    
    public function updatedSearch()
    {
        $this->resetPage(); 
    }
    public function render()
    {
        $positions = Position::search($this->search)->orderBy('name')->paginate(10);
        return view('livewire.admin.employee.position-list', compact('positions'));
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
            session()->flash('success-message', 'Data berhasil ditambahkan.');
            return redirect()->route('admin.jabatan.list');

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
            session()->flash('success-message', 'Data berhasil diubah.');
            return redirect()->route('admin.jabatan.list');

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
            $this->confirmingDelete = false;
            session()->flash('success-message', 'Data berhasil dihapus.');
            return redirect()->route('admin.jabatan.list');
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
