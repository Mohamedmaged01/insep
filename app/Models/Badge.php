<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    public $timestamps = false;
    protected $fillable = ['name_ar', 'name_en', 'icon', 'description', 'min_points'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')->withPivot('awarded_at');
    }
}
