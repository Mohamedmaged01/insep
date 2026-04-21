<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Resource;
use App\Models\LiveSession;

class Batch extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'course_id', 'instructor_id', 'start_date',
        'end_date', 'status', 'max_students',
    ];

    protected $casts = [
        'course_id' => 'integer',
        'instructor_id' => 'integer',
        'max_students' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function attendanceRecords()
    {
        return $this->hasMany(Attendance::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function liveSessions()
    {
        return $this->hasMany(LiveSession::class);
    }
}
