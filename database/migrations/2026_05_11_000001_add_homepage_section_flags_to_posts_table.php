<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('posts')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'is_breaking_news')) {
                $table->boolean('is_breaking_news')->default(false)->index()->after('status');
            }

            if (! Schema::hasColumn('posts', 'breaking_news_order')) {
                $table->unsignedSmallInteger('breaking_news_order')->nullable()->index()->after('is_breaking_news');
            }

            if (! Schema::hasColumn('posts', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->index()->after('breaking_news_order');
            }

            if (! Schema::hasColumn('posts', 'featured_order')) {
                $table->unsignedSmallInteger('featured_order')->nullable()->index()->after('is_featured');
            }

            if (! Schema::hasColumn('posts', 'is_sticky')) {
                $table->boolean('is_sticky')->default(false)->index()->after('featured_order');
            }

            if (! Schema::hasColumn('posts', 'sticky_order')) {
                $table->unsignedSmallInteger('sticky_order')->nullable()->index()->after('is_sticky');
            }

            if (! Schema::hasColumn('posts', 'is_trending')) {
                $table->boolean('is_trending')->default(false)->index()->after('sticky_order');
            }

            if (! Schema::hasColumn('posts', 'trending_order')) {
                $table->unsignedSmallInteger('trending_order')->nullable()->index()->after('is_trending');
            }

            if (! Schema::hasColumn('posts', 'is_editors_pick')) {
                $table->boolean('is_editors_pick')->default(false)->index()->after('trending_order');
            }

            if (! Schema::hasColumn('posts', 'editors_pick_order')) {
                $table->unsignedSmallInteger('editors_pick_order')->nullable()->index()->after('is_editors_pick');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('posts')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            foreach ([
                'editors_pick_order',
                'is_editors_pick',
                'trending_order',
                'is_trending',
                'sticky_order',
                'is_sticky',
                'featured_order',
                'is_featured',
                'breaking_news_order',
                'is_breaking_news',
            ] as $column) {
                if (Schema::hasColumn('posts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
