<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'name_bn',
        'name_en',
        'slug',
        'parent_id',
        'meta_title',
        'meta_description',
        'status',
        'active',
        'is_active',
        'sort_order',
        'menu_order',
        'order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'active' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_category')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function primaryPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'primary_category_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
