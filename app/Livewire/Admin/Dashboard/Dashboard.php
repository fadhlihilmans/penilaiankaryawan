<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Employee;
use App\Models\Position;
use App\Models\EvaluationCriteria;
use App\Models\EvaluationScore;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalEmployee, $totalPosition, $totalEvaluationCriteria, $employees;
    
    public function mount()
    {
        $this->getData();
    }
    
    public function getData()
    {
        $this->employees = Employee::with([
            'position',
            'evaluated' => function ($query) {
                $query->latest('created_at')->limit(10);
            }
        ])->get();
        
        $this->totalEmployee = Employee::count();
        $this->totalPosition = Position::count();
        $this->totalEvaluationCriteria = EvaluationCriteria::count();
    }
    
    public function render()
    {
        return view('livewire.admin.dashboard.dashboard');
    }
}