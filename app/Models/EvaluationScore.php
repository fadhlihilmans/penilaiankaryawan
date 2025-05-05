<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationScore extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_user_id');
    }

    public function evaluated()
    {
        return $this->belongsTo(Employee::class, 'evaluated_employee_id');
    }

    public function criteriaDetail()
    {
        return $this->belongsTo(EvaluationCriteriaDetail::class, 'evaluation_criteria_detail_id');
    }
}
