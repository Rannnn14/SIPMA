<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = [
        'year',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get currently active academic year name.
     */
    public static function getActiveYear(): string
    {
        $active = static::where('is_active', true)->orderBy('id', 'desc')->first();
        if ($active) {
            return $active->year;
        }

        // Fallback or default
        return '2026/2027';
    }

    /**
     * Set a specific academic year as active and deactivate all others.
     */
    public static function setActive(string $year): static
    {
        static::query()->update(['is_active' => false]);

        $record = static::firstOrCreate(
            ['year' => trim($year)],
            ['is_active' => true]
        );

        $record->is_active = true;
        $record->save();

        return $record;
    }
}
