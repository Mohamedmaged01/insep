<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateRequest extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'course_id', 'batch_id', 'status', 'notes',
    ];

    protected $casts = [
        'user_id'   => 'integer',
        'course_id' => 'integer',
        'batch_id'  => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
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
