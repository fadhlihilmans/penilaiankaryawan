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
        return $this->belongsTo(User::class, 'evaluated_user_id');
    }

    public function criteria()
    {
        return $this->belongsTo(EvaluationCriteriaDetail::class);
    }
}
