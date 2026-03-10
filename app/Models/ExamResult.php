<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'exam_id', 'student_id', 'score', 'attempt_number',
    ];

    protected $casts = [
        'exam_id' => 'integer',
        'student_id' => 'integer',
        'score' => 'float',
        'attempt_number' => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
