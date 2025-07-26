<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'student_id',
        'teacher_id',
        'marks',
        'total_marks',
        'date',
        'status',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}