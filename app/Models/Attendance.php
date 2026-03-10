<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public $timestamps = false;

    protected $table = 'attendance';

    protected $fillable = [
        'batch_id', 'student_id', 'date', 'status', 'absent_days', 'notes',
    ];

    protected $casts = [
        'batch_id' => 'integer',
        'student_id' => 'integer',
        'absent_days' => 'integer',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
