<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

class Course extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected array $translatable = [
        'title', 'description', 'content', 'features', 'accreditation', 'job_opportunities', 'duration',
    ];

    protected $fillable = [
        'title', 'description', 'content', 'features', 'accreditation', 'job_opportunities',
        'category', 'price', 'currency', 'duration', 'level', 'image', 'promo_video',
        'status', 'is_featured', 'home_order', 'rating', 'student_count', 'section_id',
        // bilingual variants
        'title_ar', 'title_en', 'description_ar', 'description_en', 'content_ar', 'content_en',
        'features_ar', 'features_en', 'accreditation_ar', 'accreditation_en',
        'job_opportunities_ar', 'job_opportunities_en', 'duration_ar', 'duration_en',
    ];

    protected $casts = [
        'price'         => 'float',
        'rating'        => 'float',
        'student_count' => 'integer',
        'is_featured'   => 'boolean',
        'home_order'    => 'integer',
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

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order')->orderBy('id');
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
