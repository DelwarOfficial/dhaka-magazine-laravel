<?php

namespace App\Models;

use App\Support\PostCategoryResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'needs_editorial_review' => 'boolean',
        'is_breaking_news' => 'boolean',
        'is_featured' => 'boolean',
        'is_sticky' => 'boolean',
        'is_trending' => 'boolean',
        'is_editors_pick' => 'boolean',
        'raw_import_payload' => 'array',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeBreakingNews(Builder $query): Builder
    {
        return $query->where('is_breaking_news', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeSticky(Builder $query): Builder
    {
        return $query->where('is_sticky', true);
    }

    public function scopeTrending(Builder $query): Builder
    {
        return $query->where('is_trending', true);
    }

    public function scopeEditorsPick(Builder $query): Builder
    {
        return $query->where('is_editors_pick', true);
    }

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

    public function divisionLocation(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function districtLocation(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function upazilaLocation(): BelongsTo
    {
        return $this->belongsTo(Upazila::class, 'upazila_id');
    }
}
