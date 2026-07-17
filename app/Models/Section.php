<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

class Section extends Model
{
    use HasTranslations;

    public $timestamps = false;

    // name already has _ar/_en; expose them plus description for translation
    protected array $translatable = ['name', 'description'];

    protected $fillable = ['name_ar', 'name_en', 'description', 'description_ar', 'description_en'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
