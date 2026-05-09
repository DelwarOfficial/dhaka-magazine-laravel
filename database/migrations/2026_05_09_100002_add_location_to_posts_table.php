<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add location columns to posts table.
     * These allow posts to be tagged with division/district/upazila
     * so they can be filtered on the Saradesh (সারাদেশ) page.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'division')) {
                $table->string('division')->nullable()->index();   // e.g. "Dhaka"
            }
            if (! Schema::hasColumn('posts', 'district')) {
                $table->string('district')->nullable()->index();   // e.g. "Gazipur"
            }
            if (! Schema::hasColumn('posts', 'upazila')) {
                $table->string('upazila')->nullable()->index();    // e.g. "Kaliakair"
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['division', 'district', 'upazila']);
        });
    }
};
