<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'LIKE', '%' . $keyword . '%');
        });
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }

    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    public function employees()
    {
        return $this->hasOne(Employee::class);
    }

    public function evaluator()
    {
        return $this->hasMany(EvaluationScore::class, 'evaluator_user_id');
    }

    public function getRoleBadgeAttribute()
    {
        $role = $this->roles()->first();
        if (!$role) return '';

        switch ($role->name) {
            case 'admin':
                return '<div class="badge badge-success">Admin</div>';
            case 'penilai':
                return '<div class="badge badge-primary">Penilai</div>';
            case 'karyawan':
                return '<div class="badge badge-info">Karyawan</div>';
            default:
                return '<div class="badge">Unknown</div>';
        }
    }


}
