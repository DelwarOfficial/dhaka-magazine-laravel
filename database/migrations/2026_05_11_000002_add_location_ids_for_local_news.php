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
        if (! Schema::hasTable('divisions')) {
            Schema::create('divisions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('name_bangla')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('districts') && ! Schema::hasColumn('districts', 'division_id')) {
            Schema::table('districts', function (Blueprint $table) {
                $table->unsignedBigInteger('division_id')->nullable()->index();
            });
        }

        if (! Schema::hasTable('upazilas')) {
            Schema::create('upazilas', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('division_id')->index();
                $table->unsignedBigInteger('district_id')->index();
                $table->string('name');
                $table->string('name_bangla')->nullable();
                $table->timestamps();

                $table->unique(['district_id', 'name']);
            });
        }

        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table) {
                if (! Schema::hasColumn('posts', 'division_id')) {
                    $table->unsignedBigInteger('division_id')->nullable()->index();
                }

                if (! Schema::hasColumn('posts', 'district_id')) {
                    $table->unsignedBigInteger('district_id')->nullable()->index();
                }

                if (! Schema::hasColumn('posts', 'upazila_id')) {
                    $table->unsignedBigInteger('upazila_id')->nullable()->index();
                }
            });
        }

        $this->seedLocationIds();
    }

    public function down(): void
    {
        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table) {
                foreach (['division_id', 'district_id', 'upazila_id'] as $column) {
                    if (Schema::hasColumn('posts', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('districts') && Schema::hasColumn('districts', 'division_id')) {
            Schema::table('districts', function (Blueprint $table) {
                $table->dropColumn('division_id');
            });
        }

        Schema::dropIfExists('upazilas');
        Schema::dropIfExists('divisions');
    }

    private function seedLocationIds(): void
    {
        $path = resource_path('data/bangladesh-locations.json');

        if (! file_exists($path)) {
            return;
        }

        $locationData = json_decode(file_get_contents($path), true) ?: [];
        $now = now();

        foreach ($locationData as $divisionName => $divisionData) {
            $divisionId = DB::table('divisions')->where('name', $divisionName)->value('id');

            if (! $divisionId) {
                $divisionId = DB::table('divisions')->insertGetId([
                    'name' => $divisionName,
                    'name_bangla' => $divisionData['name_bn'] ?? $divisionName,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::table('districts')
                ->where('division', $divisionName)
                ->whereNull('division_id')
                ->update(['division_id' => $divisionId, 'updated_at' => $now]);

            foreach (($divisionData['districts'] ?? []) as $districtName => $districtData) {
                $districtId = DB::table('districts')
                    ->where('division', $divisionName)
                    ->where('name', $districtName)
                    ->value('id');

                if (! $districtId) {
                    $districtId = DB::table('districts')->insertGetId([
                        'name' => $districtName,
                        'division' => $divisionName,
                        'division_id' => $divisionId,
                        'name_bangla' => $districtData['name_bn'] ?? $districtName,
                        'division_bangla' => $divisionData['name_bn'] ?? $divisionName,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                DB::table('districts')->where('id', $districtId)->update([
                    'division_id' => $divisionId,
                    'updated_at' => $now,
                ]);

                foreach (($districtData['upazilas'] ?? []) as $upazila) {
                    $name = is_array($upazila) ? ($upazila['slug'] ?? $upazila['name'] ?? '') : $upazila;

                    if ($name === '') {
                        continue;
                    }

                    if (! DB::table('upazilas')->where('district_id', $districtId)->where('name', $name)->exists()) {
                        DB::table('upazilas')->insert([
                            'division_id' => $divisionId,
                            'district_id' => $districtId,
                            'name' => $name,
                            'name_bangla' => is_array($upazila) ? ($upazila['name_bn'] ?? Str::title($name)) : Str::title($name),
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            }
        }
    }
};
