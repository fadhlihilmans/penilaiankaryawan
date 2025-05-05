<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function education()
    {
        return $this->belongsTo(Education::class, 'education_id');
    }
    public function evaluated()
    {
        return $this->hasMany(EvaluationScore::class, 'evaluated_user_id');
    }

    public function getStatusAttribute()
    {
        $status = $this->employment_status;
        if($status == 'permanent') {
            return '<div class="badge badge-success">Tetap</div>';
        }elseif($status == 'non-permanent') {
            return '<div class="badge badge-warning">Non Tetap</div>';
        }
    }
    public function getGenderTextAttribute()
    {
        $gender = $this->gender;
        if($gender == 'male') {
            return 'Laki-laki';
        }elseif($gender == 'female') {
            return 'Perempuan';
        }
    }
}
