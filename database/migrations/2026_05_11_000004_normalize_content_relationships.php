<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $this->createRelationshipTables();
        $this->addPostRelationshipColumns();
        $this->backfillPostCategories();
        $this->backfillFeaturedMedia();
        $this->backfillContentPlacements();
    }

    public function down(): void
    {
        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table) {
                foreach (['featured_media_id', 'primary_category_id'] as $column) {
                    if (Schema::hasColumn('posts', $column)) {
                        $table->dropConstrainedForeignId($column);
                    }
                }
            });
        }

        Schema::dropIfExists('content_placements');
        Schema::dropIfExists('mediables');
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('post_category');
        Schema::dropIfExists('media');
    }

    private function createRelationshipTables(): void
    {
        if (! Schema::hasTable('post_category')) {
            Schema::create('post_category', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained()->cascadeOnDelete();
                $table->foreignId('category_id')->constrained()->cascadeOnDelete();
                $table->boolean('is_primary')->default(false)->index();
                $table->timestamps();

                $table->unique(['post_id', 'category_id']);
                $table->index(['category_id', 'post_id']);
            });
        }

        if (! Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('type')->nullable()->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('post_tag')) {
            Schema::create('post_tag', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained()->cascadeOnDelete();
                $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['post_id', 'tag_id']);
                $table->index(['tag_id', 'post_id']);
            });
        }

        if (! Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->string('disk')->default('public');
                $table->string('path');
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('size')->nullable();
                $table->unsignedInteger('width')->nullable();
                $table->unsignedInteger('height')->nullable();
                $table->string('alt_text')->nullable();
                $table->text('caption')->nullable();
                $table->string('credit')->nullable();
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->unique(['disk', 'path']);
            });
        }

        if (! Schema::hasTable('mediables')) {
            Schema::create('mediables', function (Blueprint $table) {
                $table->id();
                $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
                $table->morphs('mediable');
                $table->string('collection')->default('default')->index();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->unique(['media_id', 'mediable_type', 'mediable_id', 'collection'], 'mediables_unique_attachment');
            });
        }

        if (! Schema::hasTable('content_placements')) {
            Schema::create('content_placements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained()->cascadeOnDelete();
                $table->string('placement_key')->index();
                $table->unsignedSmallInteger('sort_order')->nullable()->index();
                $table->timestamp('starts_at')->nullable()->index();
                $table->timestamp('ends_at')->nullable()->index();
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();

                $table->unique(['post_id', 'placement_key']);
                $table->index(['placement_key', 'is_active', 'sort_order']);
            });
        }
    }

    private function addPostRelationshipColumns(): void
    {
        if (! Schema::hasTable('posts')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'primary_category_id')) {
                $table->foreignId('primary_category_id')
                    ->nullable()
                    ->after('subcategory_id')
                    ->constrained('categories')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('posts', 'featured_media_id')) {
                $table->foreignId('featured_media_id')
                    ->nullable()
                    ->after('image_path')
                    ->constrained('media')
                    ->nullOnDelete();
            }
        });
    }

    private function backfillPostCategories(): void
    {
        if (! Schema::hasTable('posts') || ! Schema::hasTable('categories') || ! Schema::hasTable('post_category')) {
            return;
        }

        $categoryIdsBySlug = DB::table('categories')->pluck('id', 'slug');
        $parentIdsById = DB::table('categories')->pluck('parent_id', 'id');
        $fallbackCategoryId = $categoryIdsBySlug['others-news'] ?? DB::table('categories')->orderBy('id')->value('id');

        DB::table('posts')
            ->select(['id', 'category_id', 'subcategory_id', 'category_slug', 'subcategory_slug'])
            ->orderBy('id')
            ->chunkById(100, function ($posts) use ($categoryIdsBySlug, $parentIdsById, $fallbackCategoryId) {
                foreach ($posts as $post) {
                    $primaryId = $this->resolvePrimaryCategoryId($post, $categoryIdsBySlug, $fallbackCategoryId);

                    if (! $primaryId) {
                        continue;
                    }

                    DB::table('posts')
                        ->where('id', $post->id)
                        ->whereNull('primary_category_id')
                        ->update(['primary_category_id' => $primaryId]);

                    $categoryIds = collect([$primaryId, $parentIdsById[$primaryId] ?? null])
                        ->filter()
                        ->unique()
                        ->values();

                    foreach ($categoryIds as $categoryId) {
                        DB::table('post_category')->updateOrInsert(
                            ['post_id' => $post->id, 'category_id' => $categoryId],
                            [
                                'is_primary' => (int) $categoryId === (int) $primaryId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
            });
    }

    private function backfillFeaturedMedia(): void
    {
        if (! Schema::hasTable('posts') || ! Schema::hasTable('media') || ! Schema::hasColumn('posts', 'featured_media_id')) {
            return;
        }

        DB::table('posts')
            ->select(['id', 'title', 'image_path', 'featured_image'])
            ->whereNull('featured_media_id')
            ->orderBy('id')
            ->chunkById(100, function ($posts) {
                foreach ($posts as $post) {
                    $path = $this->normalizedMediaPath($post->image_path ?: $post->featured_image);

                    if (! $path) {
                        continue;
                    }

                    DB::table('media')->updateOrInsert(
                        ['disk' => 'public', 'path' => $path],
                        [
                            'alt_text' => $post->title,
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );

                    $mediaId = DB::table('media')->where('disk', 'public')->where('path', $path)->value('id');

                    if (! $mediaId) {
                        continue;
                    }

                    DB::table('posts')->where('id', $post->id)->update(['featured_media_id' => $mediaId]);

                    if (Schema::hasTable('mediables')) {
                        DB::table('mediables')->updateOrInsert(
                            [
                                'media_id' => $mediaId,
                                'mediable_type' => 'App\\Models\\Post',
                                'mediable_id' => $post->id,
                                'collection' => 'featured',
                            ],
                            [
                                'sort_order' => 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
            });
    }

    private function backfillContentPlacements(): void
    {
        if (! Schema::hasTable('posts') || ! Schema::hasTable('content_placements')) {
            return;
        }

        $placements = [
            'home.breaking' => ['flag' => 'is_breaking_news', 'order' => 'breaking_news_order'],
            'home.featured' => ['flag' => 'is_featured', 'order' => 'featured_order'],
            'home.sticky' => ['flag' => 'is_sticky', 'order' => 'sticky_order'],
            'home.trending' => ['flag' => 'is_trending', 'order' => 'trending_order'],
            'home.editors_pick' => ['flag' => 'is_editors_pick', 'order' => 'editors_pick_order'],
        ];

        foreach ($placements as $placementKey => $columns) {
            if (! Schema::hasColumn('posts', $columns['flag'])) {
                continue;
            }

            $query = DB::table('posts')
                ->where($columns['flag'], true)
                ->select(['id']);

            $query->when(Schema::hasColumn('posts', $columns['order']), fn ($query) => $query->addSelect($columns['order']));

            $query->orderBy('id')->chunkById(100, function ($posts) use ($placementKey, $columns) {
                foreach ($posts as $post) {
                    DB::table('content_placements')->updateOrInsert(
                        ['post_id' => $post->id, 'placement_key' => $placementKey],
                        [
                            'sort_order' => $post->{$columns['order']} ?? null,
                            'is_active' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            });
        }
    }

    private function resolvePrimaryCategoryId(object $post, \Illuminate\Support\Collection $categoryIdsBySlug, ?int $fallbackCategoryId): ?int
    {
        foreach ([$post->subcategory_id, $post->category_id] as $candidateId) {
            if ($candidateId && DB::table('categories')->where('id', $candidateId)->exists()) {
                return (int) $candidateId;
            }
        }

        foreach ([$post->subcategory_slug, $post->category_slug] as $slug) {
            $slug = trim((string) $slug);

            if ($slug !== '' && isset($categoryIdsBySlug[$slug])) {
                return (int) $categoryIdsBySlug[$slug];
            }
        }

        return $fallbackCategoryId;
    }

    private function normalizedMediaPath(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (! Str::contains($path, '/')) {
            return "images/{$path}";
        }

        return $path;
    }
};
