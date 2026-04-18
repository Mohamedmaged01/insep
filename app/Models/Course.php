<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title', 'description', 'content', 'features', 'accreditation', 'job_opportunities',
        'category', 'price', 'currency', 'duration', 'level', 'image', 'promo_video',
        'status', 'is_featured', 'rating', 'student_count', 'section_id',
    ];

    protected $casts = [
        'price'         => 'float',
        'rating'        => 'float',
        'student_count' => 'integer',
        'is_featured'   => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}
