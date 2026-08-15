<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    protected $table = 'lesson_progress';

    public $timestamps = false;

    protected $fillable = ['user_id', 'lesson_id', 'course_id', 'completed_at'];

    protected $casts = [
        'user_id'      => 'integer',
        'lesson_id'    => 'integer',
        'course_id'    => 'integer',
        'completed_at' => 'datetime',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
