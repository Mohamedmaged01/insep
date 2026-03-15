<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'description', 'amount', 'type', 'method', 'user_id', 'status', 'payment_proof',
    ];

    protected $casts = [
        'amount' => 'float',
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
