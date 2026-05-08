<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('published_at')->constrained('categories')->nullOnDelete();
            }

            if (! Schema::hasColumn('posts', 'subcategory_id')) {
                $table->foreignId('subcategory_id')->nullable()->after('category_id')->constrained('categories')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'subcategory_id')) {
                $table->dropConstrainedForeignId('subcategory_id');
            }

            if (Schema::hasColumn('posts', 'category_id')) {
                $table->dropConstrainedForeignId('category_id');
            }
        });
    }
};
