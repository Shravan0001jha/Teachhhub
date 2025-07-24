<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Teacher extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $guard_name = 'teacher';

    protected $fillable = [
        'name', 'email', 'password', 'created_by', 'created_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function batches()
    {
        return $this->hasMany(TeacherBatch::class);
    }
}
