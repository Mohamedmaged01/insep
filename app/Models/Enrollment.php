<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'student_id', 'course_id', 'batch_id', 'progress', 'grade', 'status',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'course_id' => 'integer',
        'batch_id' => 'integer',
        'progress' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
