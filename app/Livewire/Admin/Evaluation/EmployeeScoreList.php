<?php

namespace App\Livewire\Admin\Evaluation;

use Livewire\Component;
use App\Models\Employee;
use App\Models\EvaluationCriteria;
use App\Models\EvaluationCriteriaDetail;
use App\Models\EvaluationScore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeScoreList extends Component
{
    public $employees;
    public $criteria = [];
    public $selectedCriteriaIds = [];
    public $selectedPeriod = null;
    public $month;
    public $year;
    public $scores = [];
    public $comments = []; 

    public $selectingPeriod = true;
    public $viewScores = false;
    public $scoreDetails = false;
    
    public $totalScore = 0;
    public $criteriaScores = [];
    public $detailScores = [];

    protected $rules = [
        'month' => 'required|numeric|min:1|max:12',
        'year' => 'required|numeric|min:2020|max:2100',
    ];

    protected $messages = [
        'month.required' => 'Bulan harus dipilih',
        'year.required' => 'Tahun harus diisi',
    ];

    public function mount()
    {
        // Get only the current authenticated user's employee record
        $this->employees = Employee::with('user')
            ->where('user_id', Auth::id())
            ->get();
            
        $this->criteria = EvaluationCriteria::with('details')->get();
        $this->selectedCriteriaIds = $this->criteria->pluck('id')->toArray();
        
        $this->year = date('Y');
    }

    public function render()
    {
        return view('livewire.admin.evaluation.employee-score-list');
    }

    public function goToViewScores()
    {
        $this->validate([
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:2000|max:2100',
        ], [
            'month.required' => 'Bulan harus dipilih',
            'year.required' => 'Tahun harus diisi',
        ]);

        $month = str_pad($this->month, 2, '0', STR_PAD_LEFT);
        $this->selectedPeriod = $this->year . '-' . $month;
        
        // Ensure we have employee data
        if ($this->employees->isEmpty()) {
            $this->dispatch('failed-message', 'Data karyawan tidak ditemukan');
            return;
        }
        
        // Get the employee id of the authenticated user
        $employeeId = $this->employees->first()->id;
        
        $this->calculateScore($employeeId);
        $this->selectingPeriod = false;
        $this->viewScores = true;
    }

    public function calculateScore($employeeId)
    {
        $date = $this->selectedPeriod . '-01';

        $scores = EvaluationScore::with(['criteriaDetail.criteria'])
            ->where('evaluated_employee_id', $employeeId)
            ->whereDate('date', $date)
            ->get();

        $totalScore = 0;
        $criteriaScores = [];
        $detailScores = [];

        foreach ($scores as $score) {
            if (!$score->criteriaDetail || !$score->criteriaDetail->criteria) {
                continue;
            }
            
            $criteriaId = $score->criteriaDetail->criteria->id;
            $criteriaName = $score->criteriaDetail->criteria->name;
            $criteriaWeight = $score->criteriaDetail->criteria->weight / 100;
            
            $detailId = $score->criteriaDetail->id;
            $detailName = $score->criteriaDetail->name;
            $detailWeight = $score->criteriaDetail->weight / 100;
            $scoreValue = $score->weight;
            
            if (!isset($criteriaScores[$criteriaId])) {
                $criteriaScores[$criteriaId] = [
                    'name' => $criteriaName,
                    'weight' => $criteriaWeight,
                    'score' => 0,
                    'weighted_score' => 0,
                    'details' => []
                ];
            }
            
            $detailWeightedScore = $scoreValue * $detailWeight;
            $criteriaScores[$criteriaId]['details'][$detailId] = [
                'name' => $detailName,
                'weight' => $detailWeight,
                'score' => $scoreValue,
                'weighted_score' => $detailWeightedScore
            ];
            
            $criteriaScores[$criteriaId]['score'] += $detailWeightedScore;
            
            $detailScores[$detailId] = [
                'name' => $detailName,
                'weight' => $detailWeight * 100,
                'criteria_name' => $criteriaName,
                'criteria_weight' => $criteriaWeight * 100,
                'score' => $scoreValue,
                'comment' => $score->comment,
                'evaluator' => $score->evaluator ? $score->evaluator->name : 'Unknown'
            ];
        }

        foreach ($criteriaScores as $criteriaId => $criteriaData) {
            $weightedScore = $criteriaData['score'] * $criteriaData['weight'];
            $criteriaScores[$criteriaId]['weighted_score'] = $weightedScore;
            $totalScore += $weightedScore;
        }

        $this->totalScore = $totalScore;
        $this->criteriaScores = $criteriaScores;
        $this->detailScores = $detailScores;
    }

    public function viewScoreDetails()
    {
        $this->viewScores = false;
        $this->scoreDetails = true;
    }

    public function backToScores()
    {
        $this->scoreDetails = false;
        $this->viewScores = true;
    }

    public function resetForm()
    {
        $this->selectingPeriod = true;
        $this->viewScores = false;
        $this->scoreDetails = false;
        $this->selectedPeriod = null;
        $this->scores = [];
        $this->comments = [];
    }

    public function getMonthNameProperty()
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $months[(int)$this->month] ?? '';
    }
}