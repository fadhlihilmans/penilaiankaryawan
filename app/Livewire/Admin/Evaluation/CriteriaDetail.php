<?php

namespace App\Livewire\Admin\Evaluation;

use App\Models\EvaluationCriteriaDetail;
use Livewire\Component;

class CriteriaDetail extends Component
{
    public $criteriaId;
    public $search = "";
    public $criteria_detail_id;
    public $name, $description, $weight;
    public $formAdd = false, $formEdit = false, $confirmingDelete = false;

    public function mount($id)
    {
        $this->criteriaId = $id;
    }
    public function updatedSearch()
    {
        $this->resetPage(); 
    }
    public function render()
    {
        $criterias = EvaluationCriteriaDetail::with('criteria')->search($this->search)->where('evaluation_criteria_id', $this->criteriaId)->get();
        
        return view('livewire.admin.evaluation.criteria-detail', compact('criterias'));
    }

    public function add()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:evaluation_criteria_details,name,NULL,id,evaluation_criteria_id,' . $this->criteriaId,
                'description' => 'required',
                'weight' => 'required|numeric|min:0',
            ]);
        
            $totalWeight = EvaluationCriteriaDetail::where('evaluation_criteria_id', $this->criteriaId)->sum('weight');
        
            if ($totalWeight + $this->weight > 100) {
                $this->dispatch('failed-message', 'Total bobot tidak boleh melebihi 100%.');
                return;
            }
        
            EvaluationCriteriaDetail::create([
                'evaluation_criteria_id' => $this->criteriaId,
                'name' => $this->name,
                'description' => $this->description,
                'weight' => $this->weight,
            ]);
        
            $this->resetForm();
            session()->flash('success-message', 'Data berhasil ditambahkan.');
            return redirect()->route('admin.evaluasi-kriteria.detail', ['id' => $this->criteriaId]);
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }
    
    public function edit($id)
    {
        $this->formEdit = true;
        $detail = EvaluationCriteriaDetail::findOrFail($id);
        $this->criteria_detail_id = $detail->id;
        $this->name = $detail->name;
        $this->description = $detail->description;
        $this->weight = $detail->weight;
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:evaluation_criteria_details,name,' . $this->criteria_detail_id . ',id,evaluation_criteria_id,' . $this->criteriaId,
                'description' => 'required',
                'weight' => 'required|numeric|min:0',
            ]);
    
            $current = EvaluationCriteriaDetail::findOrFail($this->criteria_detail_id);
            $totalWeight = EvaluationCriteriaDetail::where('evaluation_criteria_id', $this->criteriaId)
                ->where('id', '!=', $this->criteria_detail_id)
                ->sum('weight');
    
            if ($totalWeight + $this->weight > 100) {
                $this->dispatch('failed-message', 'Total bobot tidak boleh melebihi 100%.');
                return;
            }
    
            $current->update([
                'name' => $this->name,
                'description' => $this->description,
                'weight' => $this->weight,
            ]);
    
            $this->resetForm();
            session()->flash('success-message', 'Data berhasil diubah.');
            return redirect()->route('admin.evaluasi-kriteria.detail', ['id' => $this->criteriaId]);
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->criteria_detail_id = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        $detail = EvaluationCriteriaDetail::findOrFail($this->criteria_detail_id);
        $detail->delete();
        $this->confirmingDelete = false;
        session()->flash('success-message', 'Data berhasil dihapus.');
        return redirect()->route('admin.evaluasi-kriteria.detail', ['id' => $this->criteriaId]);
    }

    public function resetForm()
    {
        $this->name = null;
        $this->description = null;
        $this->weight = null;
        $this->formAdd = false;
        $this->formEdit = false;
        $this->criteria_detail_id = null;
    }
}
