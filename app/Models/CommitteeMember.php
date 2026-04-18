<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    protected $fillable = ['name', 'title', 'specialization', 'bio', 'image', 'order'];
}
