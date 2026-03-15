<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    public $timestamps = false;
    protected $fillable = ['student_id', 'amount', 'reason', 'created_at'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
