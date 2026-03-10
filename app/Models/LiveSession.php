<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveSession extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title', 'live_url', 'batch_id', 'instructor_id',
        'scheduled_at', 'status',
    ];

    protected $casts = [
        'batch_id' => 'integer',
        'instructor_id' => 'integer',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
