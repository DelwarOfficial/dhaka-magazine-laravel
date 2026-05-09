<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * District Model
 * Represents a Bangladesh district with its division.
 * Used to power the জেলার সংবাদ location filter on the Saradesh page.
 */
class District extends Model
{
    protected $guarded = [];

    /**
     * Get all unique divisions as name => name_bangla pairs.
     */
    public static function allDivisions(): array
    {
        return static::query()
            ->select('division', 'division_bangla')
            ->distinct()
            ->orderBy('division')
            ->pluck('division_bangla', 'division')
            ->toArray();
    }

    /**
     * Get all districts for a given division.
     */
    public static function forDivision(string $division): array
    {
        return static::where('division', $division)
            ->orderBy('name')
            ->get(['name', 'name_bangla'])
            ->toArray();
    }
}
