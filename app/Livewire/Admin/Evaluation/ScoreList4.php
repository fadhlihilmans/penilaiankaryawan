<?php

namespace App\Livewire\Admin\Evaluation;

use Livewire\Component;
use App\Models\Employee;
use App\Models\EvaluationCriteria;
use App\Models\EvaluationCriteriaDetail;
use App\Models\EvaluationScore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScoreList extends Component
{
    public $employees = [];
    public $criteria = [];
    public $selectedCriteriaIds = [];
    public $selectedPeriod = null;
    public $month;
    public $year;
    public $selectedEmployeeId = null;
    public $scores = [];
    public $comments = []; // For storing comments
    
    // Form states
    public $selectingPeriod = true;
    public $selectingCriteria = false;
    public $scoringEmployee = false;
    public $employeeScores = [];
    public $employeeList = false;
    public $scoreDetails = false;
    public $selectedEmployee = null;
    
    // For score calculation and summary
    public $totalScore = 0;
    public $criteriaScores = [];
    public $detailScores = [];
    
    // For results viewing
    public $viewingResults = false;
    public $resultEmployeeId = null;
    public $resultPeriod = null;
    
    protected $rules = [
        'month' => 'required|numeric|min:1|max:12',
        'year' => 'required|numeric|min:2020|max:2100',
        'selectedCriteriaIds' => 'required|array|min:1',
        'selectedEmployeeId' => 'required|exists:employees,id',
    ];
    
    protected $messages = [
        'month.required' => 'Bulan harus dipilih',
        'year.required' => 'Tahun harus diisi',
        'selectedCriteriaIds.required' => 'Minimal satu kriteria harus dipilih',
        'selectedCriteriaIds.min' => 'Minimal satu kriteria harus dipilih',
        'selectedEmployeeId.required' => 'Karyawan harus dipilih',
    ];

    public function mount()
    {
        $this->employees = Employee::with('user')->orderBy('nip')->get();
        $this->criteria = EvaluationCriteria::with('details')->get();
        // $this->selectedCriteriaIds = $this->criteria->pluck('id')->toArray();
        // $this->month = date('m');
        // $this->year = date('Y');
    }

    public function render()
    {
        return view('livewire.admin.evaluation.score-list');
    }
    
    public function savePeriodAndCriteria()
    {
        $this->validate([
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:2020|max:2100',
            'selectedCriteriaIds' => 'required|array|min:1'
        ]);
        
        $this->selectedPeriod = $this->year . '-' . str_pad($this->month, 2, '0', STR_PAD_LEFT);
        $this->selectingPeriod = false;
        $this->selectingCriteria = false;
        $this->employeeList = true;
    }
    
    public function goToSelectCriteria()
    {
        $this->validate([
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:2020|max:2100',
        ]);
        
        $this->selectedPeriod = $this->year . '-' . str_pad($this->month, 2, '0', STR_PAD_LEFT);
        $this->selectingPeriod = false;
        $this->selectingCriteria = true;
    }
    
    public function startScoring($employeeId)
    {
        $this->selectedEmployeeId = $employeeId;
        $this->selectedEmployee = Employee::with('user', 'position')->find($employeeId);
        
        // Initialize scores array for this employee
        $this->scores = [];
        $this->comments = [];
        
        // Get selected criteria with their details
        $selectedCriteria = EvaluationCriteria::with(['details' => function($query) {
            $query->orderBy('name');
        }])->whereIn('id', $this->selectedCriteriaIds)->get();
        
        // Check if there are existing scores for this period and employee
        $existingScores = EvaluationScore::where('evaluated_employee_id', $employeeId)
            ->whereDate('date', $this->selectedPeriod . '-01')
            ->get();
        
        foreach ($selectedCriteria as $criterion) {
            foreach ($criterion->details as $detail) {
                $existingScore = $existingScores->where('evaluation_criteria_detail_id', $detail->id)->first();
                if ($existingScore) {
                    $this->scores[$detail->id] = $existingScore->weight;
                    $this->comments[$detail->id] = $existingScore->comment;
                } else {
                    $this->scores[$detail->id] = null;
                    $this->comments[$detail->id] = null;
                }
            }
        }
        
        $this->employeeList = false;
        $this->scoringEmployee = true;
    }
    
    public function saveScores()
    {
        // Validate that all scores are filled
        $rules = [];
        foreach ($this->scores as $detailId => $score) {
            $rules["scores.$detailId"] = 'required|numeric|min:0|max:100';
        }
        
        $this->validate($rules, [
            'scores.*.required' => 'Semua nilai harus diisi',
            'scores.*.numeric' => 'Nilai harus berupa angka',
            'scores.*.min' => 'Nilai minimum adalah 0',
            'scores.*.max' => 'Nilai maksimum adalah 100',
        ]);
        
        try {
            DB::beginTransaction();
            
            $date = $this->selectedPeriod . '-01'; // First day of the selected month
            
            foreach ($this->scores as $detailId => $score) {
                $detail = EvaluationCriteriaDetail::find($detailId);
                
                if (!$detail) {
                    throw new \Exception("Detail kriteria tidak ditemukan");
                }
                
                // Check if score exists
                $existingScore = EvaluationScore::where('evaluated_employee_id', $this->selectedEmployeeId)
                    ->where('evaluation_criteria_detail_id', $detailId)
                    ->whereDate('date', $date)
                    ->first();
                
                if ($existingScore) {
                    $existingScore->update([
                        'weight' => $score,
                        'comment' => $this->comments[$detailId] ?? null
                    ]);
                } else {
                    EvaluationScore::create([
                        'evaluator_user_id' => Auth::id(),
                        'evaluated_employee_id' => $this->selectedEmployeeId,
                        'evaluation_criteria_detail_id' => $detailId,
                        'comment' => $this->comments[$detailId] ?? null,
                        'date' => $date,
                        'weight' => $score
                    ]);
                }
            }
            
            DB::commit();
            
            $this->calculateScore($this->selectedEmployeeId);
            $this->scoringEmployee = false;
            $this->scoreDetails = true;
            $this->dispatch('success-message', 'Penilaian berhasil disimpan');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
        
        // Group scores by criteria
        foreach ($scores as $score) {
            if (!$score->criteriaDetail || !$score->criteriaDetail->criteria) {
                continue; 
            }
            
            $criteriaId = $score->criteriaDetail->criteria->id;
            $criteriaName = $score->criteriaDetail->criteria->name;
            $criteriaWeight = $score->criteriaDetail->criteria->weight / 100; // Convert to decimal
            
            $detailId = $score->criteriaDetail->id;
            $detailName = $score->criteriaDetail->name;
            $detailWeight = $score->criteriaDetail->weight / 100; // Convert to decimal
            $scoreValue = $score->weight;
            
            // Initialize if not exists
            if (!isset($criteriaScores[$criteriaId])) {
                $criteriaScores[$criteriaId] = [
                    'name' => $criteriaName,
                    'weight' => $criteriaWeight,
                    'score' => 0,
                    'weighted_score' => 0,
                    'details' => []
                ];
            }
            
            // Add detail score
            $detailWeightedScore = $scoreValue * $detailWeight;
            $criteriaScores[$criteriaId]['details'][$detailId] = [
                'name' => $detailName,
                'weight' => $detailWeight,
                'score' => $scoreValue,
                'weighted_score' => $detailWeightedScore
            ];
            
            // Accumulate score for this criteria
            $criteriaScores[$criteriaId]['score'] += $detailWeightedScore;
            
            // Store individual detail scores for display
            $detailScores[$detailId] = [
                'name' => $detailName,
                'weight' => $detailWeight * 100, // Back to percentage for display
                'criteria_name' => $criteriaName,
                'criteria_weight' => $criteriaWeight * 100, // Back to percentage for display
                'score' => $scoreValue,
                'comment' => $score->comment
            ];
        }
        
        // Calculate final weighted scores for each criteria and total
        foreach ($criteriaScores as $criteriaId => $criteriaData) {
            $weightedScore = $criteriaData['score'] * $criteriaData['weight'];
            $criteriaScores[$criteriaId]['weighted_score'] = $weightedScore;
            $totalScore += $weightedScore;
        }
        
        $this->totalScore = $totalScore;
        $this->criteriaScores = $criteriaScores;
        $this->detailScores = $detailScores;
    }
    
    public function viewResults($employeeId)
    {
        $this->resultEmployeeId = $employeeId;
        $this->selectedEmployee = Employee::with('user', 'position')->find($employeeId);
        
        if (!$this->selectedEmployee) {
            $this->dispatch('failed-message', 'Karyawan tidak ditemukan');
            return;
        }
        
        $this->calculateScore($employeeId);
        $this->employeeList = false;
        $this->scoreDetails = true;
    }
    
    public function backToEmployeeList()
    {
        $this->scoringEmployee = false;
        $this->scoreDetails = false;
        $this->employeeList = true;
    }
    
    public function resetForm()
    {
        $this->selectingPeriod = true;
        $this->selectingCriteria = false;
        $this->scoringEmployee = false;
        $this->employeeList = false;
        $this->scoreDetails = false;
        $this->selectedPeriod = null;
        $this->selectedCriteriaIds = [];
        $this->selectedEmployeeId = null;
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