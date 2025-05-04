<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluationCriteriaDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function criteria()
    {
        return $this->belongsTo(EvaluationCriteria::class, 'evaluation_criteria_id');
    }

    public function scores()
    {
        return $this->hasMany(EvaluationScore::class);
    }
}
