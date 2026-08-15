<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

class Lesson extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected array $translatable = ['title'];

    protected $fillable = [
        'course_id', 'title', 'title_ar', 'title_en', 'order',
        'video_url', 'duration', 'is_preview', 'attachments', 'quiz',
    ];

    protected $casts = [
        'course_id'   => 'integer',
        'order'       => 'integer',
        'is_preview'  => 'boolean',
        'attachments' => 'array',
        'quiz'        => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }
}
