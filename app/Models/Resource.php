<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title', 'type', 'file_url', 'size', 'instructor_id',
        'course_id', 'batch_id', 'downloads',
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
