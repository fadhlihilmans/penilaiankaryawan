<?php

namespace App\Livewire\Admin\Evaluation;

use App\Models\EvaluationScore;
use Livewire\Component;

class ScoreList2 extends Component
{
    public $scores;
    public $evaluator_id, $evaluated_id, $criteria_id, $date, $comment, $score, $score_id;
    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public function mount()
    {
        $this->getData();
    }
    public function getData()
    {
        $this->scores = EvaluationScore::with('evaluator', 'evaluated', 'criteria')->get();
    }
    public function render()
    {
        return view('livewire.admin.evaluation.score-list');
    }
    public function add()
    {
        try {
            $this->validate([
               'evaluator_id' => 'required',
               'evaluated_id' => 'required',
               'criteria_id' => 'required',
               'date' => 'required',
               'comment' => 'nullable',
               'score' => 'required',
            ], [
                'evaluator_id.required' => 'Penilai wajib diisi.',
                'evaluated_id.required' => 'Dinilai wajib diisi.',
                'criteria_id.required' => 'Kriteria wajib diisi.',
                'date.required' => 'Tanggal wajib diisi.',
                'score.required' => 'Nilai wajib diisi.',
            ]);

            EvaluationScore::create([
                'evaluator_id' => $this->evaluator_id,
                'evaluated_id' => $this->evaluated_id,
                'criteria_id' => $this->criteria_id,
                'date' => $this->date,
                'comment' => $this->comment,
                'score' => $this->score,
            ]);

            $this->resetForm();
            $this->getData();
            $this->dispatch('success-message', 'Penilaian berhasil ditambahkan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi Kesalahan : ' . $th->getMessage());
        }

    }
    public function edit($id)
    {
        try {
            $score = EvaluationScore::findOrFail($id);
            $this->formEdit = true;
            $this->score_id = $score->id;
            $this->evaluator_id = $score->evaluator_id;
            $this->evaluated_id = $score->evaluated_id;
            $this->criteria_id = $score->criteria_id;
            $this->date = $score->date;
            $this->comment = $score->comment;
            $this->score = $score->score;
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi Kesalahan : ' . $th->getMessage());
        }
    }

    public function update()
    {
        try {
            $this->validate([
                'evaluator_id' => 'required',
                'evaluated_id' => 'required',
                'criteria_id' => 'required',
                'date' => 'required',
                'comment' => 'nullable',
                'score' => 'required',
            ], [
                'evaluator_id.required' => 'Penilai wajib diisi.',
                'evaluated_id.required' => 'Dinilai wajib diisi.',
                'criteria_id.required' => 'Kriteria wajib diisi.',
                'date.required' => 'Tanggal wajib diisi.',
                'score.required' => 'Nilai wajib diisi.',
            ]);

            $score = EvaluationScore::findOrFail($this->score_id);
            $score->update([
                'evaluator_id' => $this->evaluator_id,
                'evaluated_id' => $this->evaluated_id,
                'criteria_id' => $this->criteria_id,
                'date' => $this->date,
                'comment' => $this->comment,
                'score' => $this->score,
            ]);

            $this->resetForm();
            $this->getData();
            $this->dispatch('success-message', 'Penilaian berhasil diubah.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi Kesalahan : ' . $th->getMessage());
        }
    }

    public function confirmingDelete($id)
    {
        $this->score_id = $id;
        $this->confirmingDelete = true;
    }
    public function deleteConfirmed()
    {
        try {
            $score = EvaluationScore::findOrFail($this->score_id);
            $score->delete();
            $this->getData();
            $this->confirmingDelete = false;
            $this->dispatch('success-message', 'User berhasil dihapus.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi Kesalahan : ' . $th->getMessage());
        }
    }

    public function calculateTotalScore($employeeId, $month)
    {
        $scores = EvaluationScore::with('criteria.criteria') 
            ->where('evaluated_user_id', $employeeId)
            ->whereMonth('date', $month)
            ->get();

        $grouped = [];

        foreach ($scores as $score) {
            $mainCriteria = $score->criteria->criteria; 
            $mainCriteriaId = $mainCriteria->id;

            if (!isset($grouped[$mainCriteriaId])) {
                $grouped[$mainCriteriaId] = [
                    'weight' => $mainCriteria->weight,
                    'total' => 0,
                ];
            }

            $subScore = $score->score * ($score->criteria->weight / 100);
            $grouped[$mainCriteriaId]['total'] += $subScore;
        }

        $finalScore = 0;
        foreach ($grouped as $data) {
            $finalScore += $data['total'] * ($data['weight'] / 100);
        }

        return round($finalScore, 2);
    }

    public function resetForm()
    {
        $this->formAdd = false;
        $this->formEdit = false;
        $this->evaluator_id = null;
        $this->evaluated_id = null;
        $this->criteria_id = null;
        $this->date = null;
        $this->comment = null;
        $this->score = null;
    }
}
