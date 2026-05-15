<?php

namespace App\Models;

use App\Support\PostCategoryResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'content',
        'featured_image',
        'image_path',
        'source_url',
        'source_name',
        'published_at',
        'author_id',
        'category_id',
        'subcategory_id',
        'primary_category_id',
        'category_slug',
        'subcategory_slug',
        'category',
        'division',
        'district',
        'upazila',
        'division_id',
        'district_id',
        'upazila_id',
        'featured_media_id',
        'meta_title',
        'meta_description',
        'status',
        'needs_editorial_review',
        'is_breaking_news',
        'breaking_news_order',
        'is_featured',
        'featured_order',
        'is_sticky',
        'is_photocard',
        'sticky_order',
        'is_trending',
        'trending_order',
        'is_editors_pick',
        'editors_pick_order',
        'view_count',
        'post_format',
        'raw_import_payload',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'needs_editorial_review' => 'boolean',
        'is_breaking_news' => 'boolean',
        'is_featured' => 'boolean',
        'is_sticky' => 'boolean',
        'is_photocard' => 'boolean',
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

    public function scopePhotocard(Builder $query): Builder
    {
        return $query->where('is_photocard', true);
    }

    public function scopeTrending(Builder $query): Builder
    {
        return $query->where('is_trending', true);
    }

    public function scopeEditorsPick(Builder $query): Builder
    {
        return $query->where('is_editors_pick', true);
    }

    public function scopeInCategories(Builder $query, array $slugs): Builder
    {
        return $query->whereHas('categories', fn (Builder $categoryQuery) => $categoryQuery->whereIn('slug', $slugs));
    }

    public function scopeWithContentRelations(Builder $query): Builder
    {
        return $query->with([
            'author',
            'primaryCategory.parent',
            'categories.parent',
            'category.parent',
            'subcategory.parent',
            'featuredMedia',
            'tags',
        ]);
    }

    public function scopeLocalLocated(Builder $query): Builder
    {
        return $query
            ->whereNotNull('division_id')
            ->whereNotNull('district_id')
            ->whereNotNull('upazila_id')
            ->whereHas('districtLocation', function (Builder $locationQuery) {
                $locationQuery->whereColumn('districts.division_id', 'posts.division_id');
            })
            ->whereHas('upazilaLocation', function (Builder $locationQuery) {
                $locationQuery->whereColumn('upazilas.division_id', 'posts.division_id')
                    ->whereColumn('upazilas.district_id', 'posts.district_id');
            });
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

    public function primaryCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'primary_category_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_category')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag')->withTimestamps();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function upazila(): BelongsTo
    {
        return $this->belongsTo(Upazila::class, 'upazila_id');
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

    public function featuredMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_media_id');
    }

    public function media(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'mediable')
            ->withPivot(['collection', 'sort_order'])
            ->withTimestamps();
    }

    public function placements(): HasMany
    {
        return $this->hasMany(ContentPlacement::class);
    }
}
