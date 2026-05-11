<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphedByMany;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'disk',
        'path',
        'mime_type',
        'size',
        'width',
        'height',
        'alt_text',
        'caption',
        'credit',
        'uploaded_by',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function posts(): MorphedByMany
    {
        return $this->morphedByMany(Post::class, 'mediable')
            ->withPivot(['collection', 'sort_order'])
            ->withTimestamps();
    }
}
