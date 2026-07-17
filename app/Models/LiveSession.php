<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

class LiveSession extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected array $translatable = ['title'];

    protected $fillable = [
        'title', 'live_url', 'batch_id', 'instructor_id',
        'scheduled_at', 'status',
        'title_ar', 'title_en',
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
