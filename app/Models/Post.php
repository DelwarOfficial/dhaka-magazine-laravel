<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'needs_editorial_review' => 'boolean',
        'raw_import_payload' => 'array',
    ];
}
