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
        if (! Schema::hasTable('posts')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'content')) {
                $table->longText('content')->nullable()->after('excerpt');
            }

            if (! Schema::hasColumn('posts', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->index()->after('content');
            }

            if (! Schema::hasColumn('posts', 'category')) {
                $table->string('category')->nullable()->index()->after('author_id');
            }

            if (! Schema::hasColumn('posts', 'image_path')) {
                $table->string('image_path')->nullable()->index()->after('category');
            }

            if (! Schema::hasColumn('posts', 'view_count')) {
                $table->unsignedBigInteger('view_count')->default(0)->index()->after('image_path');
            }
        });

        $this->backfillLiveFields();
    }

    public function down(): void
    {
        if (! Schema::hasTable('posts')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            foreach (['view_count', 'image_path', 'category', 'author_id', 'content'] as $column) {
                if (Schema::hasColumn('posts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    private function backfillLiveFields(): void
    {
        $fallbackAuthorId = Schema::hasTable('users') ? DB::table('users')->value('id') : null;

        DB::table('posts')
            ->select(['id', 'body', 'category_slug', 'featured_image', 'source_name'])
            ->orderBy('id')
            ->chunkById(100, function ($posts) use ($fallbackAuthorId) {
                foreach ($posts as $post) {
                    DB::table('posts')
                        ->where('id', $post->id)
                        ->update([
                            'content' => $post->body,
                            'author_id' => $fallbackAuthorId,
                            'category' => $post->category_slug,
                            'image_path' => $this->publicImageFilename($post->featured_image),
                            'view_count' => 0,
                        ]);
                }
            });
    }

    private function publicImageFilename(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $cleanPath = ltrim($path, '/');

        if (Str::startsWith($cleanPath, 'images/')) {
            return basename($cleanPath);
        }

        if (! Str::contains($cleanPath, '/') && file_exists(public_path("images/{$cleanPath}"))) {
            return $cleanPath;
        }

        return null;
    }
};
