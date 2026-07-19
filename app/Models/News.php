<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

class News extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $table = 'news';

    protected array $translatable = ['title', 'description'];

    protected $fillable = [
        'title', 'description', 'tag', 'date', 'image', 'video_url', 'form_url',
        'title_ar', 'title_en', 'description_ar', 'description_en',
    ];
}
