<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title', 'course_id', 'batch_id', 'type', 'questions', 'duration',
        'attempts', 'status', 'avg_score', 'exam_link',
    ];

    protected $casts = [
        'course_id' => 'integer',
        'batch_id'  => 'integer',
        'questions' => 'integer',
        'attempts'  => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }
}
