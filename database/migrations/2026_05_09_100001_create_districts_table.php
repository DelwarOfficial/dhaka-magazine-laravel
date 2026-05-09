<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the districts table.
     * Stores Bangladesh division/district data used for the জেলার সংবাদ filter.
     */
    public function up(): void
    {
        if (Schema::hasTable('districts')) {
            return;
        }

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // English name e.g. "Dhaka"
            $table->string('division');                      // English division e.g. "Dhaka"
            $table->string('name_bangla')->nullable();       // Bengali name e.g. "ঢাকা"
            $table->string('division_bangla')->nullable();   // Bengali division e.g. "ঢাকা"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
