<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'serial_number', 'student_id', 'course_id', 'title',
        'issue_date', 'grade', 'status',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'course_id' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
