<?php

namespace App\Livewire\Admin\Evaluation;

use App\Models\EvaluationCriteriaDetail;
use Livewire\Component;

class CriteriaDetail extends Component
{
    public $criterias;
    public $name, $description, $weight;
    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public function mount($id)
    {
        $this->getData($id);
    }
    public function getData($id)
    {
        $this->criterias = EvaluationCriteriaDetail::where('evaluation_criteria_id', $id)->get();
    }
    public function render()
    {
        return view('livewire.admin.evaluation.criteria-detail');
    }
    public function add()
    {
        try {
            $id = $this->criterias->first()->evaluation_criteria_id;

            $this->validate([
                'name' => 'required|string|max:255|unique:evaluation_criteria_details,name',
                'description' => 'required',
                'weight' => 'required',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'name.unique' => 'Nama sudah digunakan.',
                'description.required' => 'Deskripsi wajib diisi.',
                'weight.required' => 'Bobot wajib diisi.',
            ]);

            EvaluationCriteriaDetail::create([
                'evaluation_criteria_id' => $id,
                'name' => $this->name,
                'description' => $this->description,
                'weight' => $this->weight,
            ]);

            $this->reset();
            $this->getData($id);
            $this->dispatch('success-message', 'Data berhasil ditambahkan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

}
