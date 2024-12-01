<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Student extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $guard_name = 'student';

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    // public function batches()
    // {
    //     return $this->belongsToMany(StudentBatch::class, 'student_batches', 'student_id', 'batch_id');
    // }
    public function batches()
    {
        return $this->hasMany(StudentBatch::class);
    }
}
