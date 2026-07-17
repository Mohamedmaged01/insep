<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

class Resource extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected array $translatable = ['title'];

    protected $fillable = [
        'title', 'type', 'file_url', 'size', 'instructor_id',
        'course_id', 'batch_id', 'downloads',
        'title_ar', 'title_en',
    ];

    protected $casts = [
        'instructor_id' => 'integer',
        'course_id' => 'integer',
        'batch_id' => 'integer',
        'downloads' => 'integer',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
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
