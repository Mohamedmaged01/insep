<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    public $timestamps = false;

    protected $fillable = ['name_ar', 'name_en', 'description'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
