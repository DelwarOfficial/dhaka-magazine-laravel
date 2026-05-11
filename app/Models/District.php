<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

/**
 * District Model
 * Represents a Bangladesh district with its division.
 * Used to power the জেলার সংবাদ location filter on the Saradesh page.
 */
class District extends Model
{
    protected $guarded = [];

    public function divisionModel(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function upazilas(): HasMany
    {
        return $this->hasMany(Upazila::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all unique divisions as name => name_bangla pairs.
     */
    public static function allDivisions(): array
    {
        try {
            if (Schema::hasTable('districts')) {
                $divisions = static::query()
                    ->select('division', 'division_bangla')
                    ->distinct()
                    ->orderBy('division')
                    ->pluck('division_bangla', 'division')
                    ->toArray();

                if (! empty($divisions)) {
                    return $divisions;
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to fetch divisions from database: " . $e->getMessage());
            // Fall back to the bundled JSON data when the database is unavailable.
        }

        return static::fallbackDivisions();
    }

    /**
     * Get all districts for a given division.
     */
    public static function forDivision(string $division): array
    {
        try {
            if (Schema::hasTable('districts')) {
                $districts = static::query()
                    ->where('division', $division)
                    ->orderBy('name')
                    ->get(['name', 'name_bangla'])
                    ->toArray();

                if (! empty($districts)) {
                    return $districts;
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to fetch districts for division [{$division}]: " . $e->getMessage());
            // Fall back to the bundled JSON data when the database is unavailable.
        }

        return static::fallbackDistricts($division);
    }

    public static function belongsToDivision(string $division, string $district): bool
    {
        return collect(static::forDivision($division))->contains(
            fn(array $item) => ($item['name'] ?? '') === $district
        );
    }

    private static function fallbackDivisions(): array
    {
        return collect(static::locationData())
            ->mapWithKeys(fn(array $divisionData, string $division) => [
                $division => $divisionData['name_bn'] ?? $division,
            ])
            ->sortKeys()
            ->all();
    }

    private static function fallbackDistricts(string $division): array
    {
        return collect(static::locationData()[$division]['districts'] ?? [])
            ->map(fn(array $districtData, string $district) => [
                'name' => $district,
                'name_bangla' => $districtData['name_bn'] ?? $district,
            ])
            ->sortBy('name')
            ->values()
            ->all();
    }

    private static function locationData(): array
    {
        return \App\Support\LocationDataProvider::getLocationData();
    }
}
