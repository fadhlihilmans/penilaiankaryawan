<?php

namespace App\Livewire\Admin\Evaluation;

use Livewire\Component;
use App\Models\EvaluationCriteria;
use App\Models\EvaluationCriteriaDetail;

class CriteriaList extends Component
{
    public $criterias;
    public $name, $description, $weight, $criteria_id;
    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $selectedCriteriaId, $selectedCriteria;
    public function mount()
    {
        $this->getData();
    }
    public function getData()
    {
        $this->criterias = EvaluationCriteria::orderBy('name')->get();
    }
    public function render()
    {
        return view('livewire.admin.evaluation.criteria-list');
    }
    public function add()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:evaluation_criterias,name',
                'description' => 'required',
                'weight' => 'required',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'name.unique' => 'Nama sudah digunakan.',
                'description.required' => 'Deskripsi wajib diisi.',
                'weight.required' => 'Bobot wajib diisi.',
            ]);

            // max 100
            $totalWeight = EvaluationCriteria::sum('weight');
            $newWeight = floatval($this->weight);

            if ($totalWeight + $newWeight > 100) {
                $this->dispatch('failed-message', 'Total bobot tidak boleh melebihi 100%.');
                return;
            }

            EvaluationCriteria::create([
                'name' => $this->name,
                'description' => $this->description,
                'weight' => $this->weight,
            ]);

            $this->resetForm();
            $this->getData();
            session()->flash('success-message', 'Data berhasil ditambahkan.');
            return redirect()->route('admin.evaluasi-kriteria.list');

        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $this->formEdit = true;
        $criteria = EvaluationCriteria::findOrFail($id);
        $this->criteria_id = $criteria->id;
        $this->name = $criteria->name;
        $this->weight = $criteria->weight;
        $this->description = $criteria->description;   
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:evaluation_criterias,name,' . $this->criteria_id,
                'description' => 'required',
                'weight' => 'required',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'name.unique' => 'Nama sudah digunakan.',
                'description.required' => 'Deskripsi wajib diisi.',
                'weight.required' => 'Bobot wajib diisi.',
            ]);

            // max 100
            $criteria = EvaluationCriteria::findOrFail($this->criteria_id);

            $totalWeight = EvaluationCriteria::where('id', '!=', $criteria->id)->sum('weight');
            $newWeight = floatval($this->weight);
    
            if ($totalWeight + $newWeight > 100) {
                $this->dispatch('failed-message', 'Total bobot tidak boleh melebihi 100%.');
                return;
            }

            $criteria->update([
                'name' => $this->name,
                'description' => $this->description,
                'weight' => $this->weight,
            ]);

            $this->resetForm();
            $this->getData();
            session()->flash('success-message', 'Data berhasil diubah.');
            return redirect()->route('admin.evaluasi-kriteria.list');

        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }
    public function confirmDelete($id)
    {
        $this->selectedCriteriaId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $criteria = EvaluationCriteria::findOrFail($this->selectedCriteriaId);
            $criteria->delete();
            $this->getData();
            $this->confirmingDelete = false;
            session()->flash('success-message', 'User berhasil dihapus.');
            return redirect()->route('admin.evaluasi-kriteria.list');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function criteriaSetting($id)
    {
        return redirect()->route('admin.evaluasi-kriteria.detail', ['id' => $id]);
    }

    public function resetForm()
    {
        $this->formAdd = false;
        $this->formEdit = false;
        $this->criteria_id = '';
        $this->name = '';
        $this->description = '';
        $this->weight = '';
    }
}
