<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

class CommitteeMember extends Model
{
    use HasTranslations;

    protected array $translatable = ['name', 'title', 'specialization', 'bio'];

    protected $fillable = [
        'name', 'title', 'specialization', 'bio', 'image', 'order',
        'name_ar', 'name_en', 'title_ar', 'title_en',
        'specialization_ar', 'specialization_en', 'bio_ar', 'bio_en',
    ];
}
