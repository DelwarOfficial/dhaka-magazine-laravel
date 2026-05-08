<?php

namespace App\Models;

use App\Support\PostCategoryResolver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'needs_editorial_review' => 'boolean',
        'raw_import_payload' => 'array',
    ];

    public function getResolvedCategoryAttribute(): array
    {
        return PostCategoryResolver::categoryFor($this);
    }

    public function getEffectiveCategorySlugAttribute(): string
    {
        return PostCategoryResolver::effectiveSlug($this);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }
}
