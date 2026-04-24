<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'serial_number', 'student_id', 'course_id', 'batch_id', 'title',
        'issue_date', 'grade', 'status', 'file_url', 'type', 'created_by',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'course_id'  => 'integer',
        'batch_id'   => 'integer',
        'created_by' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
