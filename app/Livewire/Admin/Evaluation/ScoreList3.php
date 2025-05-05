<?php

namespace App\Livewire\Admin\Evaluation;

use App\Models\EvaluationScore;
use App\Models\EvaluationCriteria;
use App\Models\EvaluationCriteriaDetail;
use App\Models\Employee;
use App\Models\User;
use Livewire\Component;

class ScoreList3 extends Component
{
    public $scores;
    public $evaluator_user_id, $evaluated_employee_id, $evaluation_criteria_detail_id, $date, $comment, $weight, $score_id;
    public $formAdd = false, $formEdit = false, $confirmingDelete = false, $detailOpen = false;
    public $selectedEmployee, $selectedMonth, $selectedYear;
    public $criterias, $users, $employees, $criteriaDetails;
    public $selectedCriteria;

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
        $this->loadDependencies();
        $this->getData();
    }

    public function loadDependencies()
    {
        $this->criterias = EvaluationCriteria::with('details')->get();
        $this->users = User::whereHas('roles', function ($query) {
            $query->where('name', 'penilai');
        })->get();
        $this->employees = Employee::with('user')->get();
        $this->criteriaDetails = EvaluationCriteriaDetail::with('criteria')->get();
    }

    public function updatedSelectedCriteria()
    {
        if (!empty($this->selectedCriteria)) {
            $this->criteriaDetails = EvaluationCriteriaDetail::where('evaluation_criteria_id', $this->selectedCriteria)->get();
        } else {
            $this->criteriaDetails = EvaluationCriteriaDetail::with('criteria')->get();
        }
    }

    public function getData()
    {
        $this->scores = EvaluationScore::with('evaluator', 'evaluated', 'criteriaDetail.criteria')
            ->when($this->selectedMonth, function($query) {
                return $query->whereMonth('date', $this->selectedMonth);
            })
            ->when($this->selectedYear, function($query) {
                return $query->whereYear('date', $this->selectedYear);
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.evaluation.score-list3');
    }

    public function add()
    {
        dd($this->selectedCriteria);
        try {
            $this->validate([
               'evaluator_user_id' => 'required',
               'evaluated_employee_id' => 'required',
               'evaluation_criteria_detail_id' => 'required',
               'date' => 'required',
               'comment' => 'nullable',
               'weight' => 'required|numeric|min:0|max:100',
            ], [
                'evaluator_user_id.required' => 'Penilai wajib diisi.',
                'evaluated_employee_id.required' => 'Karyawan yang dinilai wajib diisi.',
                'evaluation_criteria_detail_id.required' => 'Kriteria penilaian wajib diisi.',
                'date.required' => 'Tanggal wajib diisi.',
                'weight.required' => 'Nilai wajib diisi.',
                'weight.numeric' => 'Nilai harus berupa angka.',
                'weight.min' => 'Nilai minimal 0.',
                'weight.max' => 'Nilai maksimal 100.',
            ]);

            EvaluationScore::create([
                'evaluator_user_id' => $this->evaluator_user_id,
                'evaluated_employee_id' => $this->evaluated_employee_id,
                'evaluation_criteria_detail_id' => $this->evaluation_criteria_detail_id,
                'date' => $this->date,
                'comment' => $this->comment,
                'weight' => $this->weight,
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
            $this->evaluator_user_id = $score->evaluator_user_id;
            $this->evaluated_employee_id = $score->evaluated_employee_id;
            $this->evaluation_criteria_detail_id = $score->evaluation_criteria_detail_id;
            $this->date = $score->date;
            $this->comment = $score->comment;
            $this->weight = $score->weight;

            // Get the criteria ID for the selected detail
            $detail = EvaluationCriteriaDetail::find($score->evaluation_criteria_detail_id);
            if ($detail) {
                $this->selectedCriteria = $detail->evaluation_criteria_id;
                $this->updatedSelectedCriteria();
            }
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi Kesalahan : ' . $th->getMessage());
        }
    }

    public function update()
    {
        try {
            $this->validate([
                'evaluator_user_id' => 'required',
                'evaluated_employee_id' => 'required',
                'evaluation_criteria_detail_id' => 'required',
                'date' => 'required',
                'comment' => 'nullable',
                'weight' => 'required|numeric|min:0|max:100',
            ], [
                'evaluator_user_id.required' => 'Penilai wajib diisi.',
                'evaluated_employee_id.required' => 'Karyawan yang dinilai wajib diisi.',
                'evaluation_criteria_detail_id.required' => 'Kriteria penilaian wajib diisi.',
                'date.required' => 'Tanggal wajib diisi.',
                'weight.required' => 'Nilai wajib diisi.',
                'weight.numeric' => 'Nilai harus berupa angka.',
                'weight.min' => 'Nilai minimal 0.',
                'weight.max' => 'Nilai maksimal 100.',
            ]);

            $score = EvaluationScore::findOrFail($this->score_id);
            $score->update([
                'evaluator_user_id' => $this->evaluator_user_id,
                'evaluated_employee_id' => $this->evaluated_employee_id,
                'evaluation_criteria_detail_id' => $this->evaluation_criteria_detail_id,
                'date' => $this->date,
                'comment' => $this->comment,
                'weight' => $this->weight,
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
            $this->dispatch('success-message', 'Penilaian berhasil dihapus.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi Kesalahan : ' . $th->getMessage());
        }
    }

    public function viewDetail($employeeId)
    {
        $this->selectedEmployee = Employee::with('user')->findOrFail($employeeId);
        $this->detailOpen = true;
    }

    public function calculateTotalScore($employeeId, $month, $year = null)
    {
        $year = $year ?? date('Y');
        
        $scores = EvaluationScore::with('criteriaDetail.criteria') 
            ->where('evaluated_employee_id', $employeeId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        if ($scores->isEmpty()) {
            return 0;
        }

        $groupedByCriteria = [];

        foreach ($scores as $score) {
            $mainCriteria = $score->criteriaDetail->criteria; 
            $mainCriteriaId = $mainCriteria->id;
            $detailId = $score->criteriaDetail->id;

            if (!isset($groupedByCriteria[$mainCriteriaId])) {
                $groupedByCriteria[$mainCriteriaId] = [
                    'name' => $mainCriteria->name,
                    'weight' => $mainCriteria->weight,
                    'details' => [],
                    'total' => 0,
                ];
            }

            if (!isset($groupedByCriteria[$mainCriteriaId]['details'][$detailId])) {
                $groupedByCriteria[$mainCriteriaId]['details'][$detailId] = [
                    'name' => $score->criteriaDetail->name,
                    'weight' => $score->criteriaDetail->weight,
                    'score' => $score->weight
                ];
            }

            // Calculate weighted score for this detail
            $detailScore = $score->weight * ($score->criteriaDetail->weight / 100);
            $groupedByCriteria[$mainCriteriaId]['total'] += $detailScore;
        }

        $finalScore = 0;
        foreach ($groupedByCriteria as $criteriaId => $criteriaData) {
            // Calculate the weighted score for this main criteria
            $criteriaWeightedScore = $criteriaData['total'] * ($criteriaData['weight'] / 100);
            $finalScore += $criteriaWeightedScore;
            
            // Store the weighted score back for detailed reporting
            $groupedByCriteria[$criteriaId]['weighted_score'] = $criteriaWeightedScore;
        }

        return [
            'total_score' => round($finalScore, 2),
            'detailed_scores' => $groupedByCriteria
        ];
    }

    public function getEmployeeScoreSummary($employeeId)
    {
        $month = $this->selectedMonth ?: date('m');
        $year = $this->selectedYear ?: date('Y');
        
        return $this->calculateTotalScore($employeeId, $month, $year);
    }

    public function filterByMonth()
    {
        $this->getData();
    }

    public function resetForm()
    {
        $this->formAdd = false;
        $this->formEdit = false;
        $this->confirmingDelete = false;
        $this->detailOpen = false;
        $this->selectedEmployee = null;
        $this->evaluator_user_id = null;
        $this->evaluated_employee_id = null;
        $this->evaluation_criteria_detail_id = null;
        $this->selectedCriteria = null;
        $this->date = now()->format('Y-m-d');
        $this->comment = null;
        $this->weight = null;
        $this->score_id = null;
    }
}