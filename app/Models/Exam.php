<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

class Exam extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected array $translatable = ['title'];

    protected $fillable = [
        'title', 'course_id', 'batch_id', 'type', 'questions', 'duration',
        'attempts', 'status', 'avg_score', 'exam_link',
        'title_ar', 'title_en',
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
