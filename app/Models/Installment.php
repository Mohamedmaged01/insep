<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    public $timestamps = false;
    protected $fillable = ['student_id', 'batch_id', 'course_id', 'total_amount', 'paid_amount', 'due_date', 'status', 'notes'];
    protected $casts = ['total_amount' => 'float', 'paid_amount' => 'float'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
