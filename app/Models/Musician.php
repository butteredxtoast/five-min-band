<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Musician
 *
 * @mixin Builder
 * @property int $id
 * @property string $name
 * @property array $instruments
 * @property string|null $other
 * @property boolean $vocalist
 * @property boolean $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Musician extends Model
{
    use HasFactory;

    protected $table = 'musicians';
    protected $fillable = [
        'name',
        'instruments',
        'other',
        'vocalist',
        'is_active'
    ];

    protected $casts = [
        'instruments' => 'array',
        'is_active' => 'boolean'
    ];

    public static function create(array $attributes = [])
    {
        if (!isset($attributes['instruments'])) {
            return static::query()->create([
                ...$attributes,
                'vocalist' => false,
                'is_active' => $attributes['is_active'] ?? true
            ]);
        }

        $instruments = array_map('strtolower', $attributes['instruments']);
        $isVocalist = in_array('vocals', $instruments);

        $instrumentsWithoutVocals = array_values(
            array_filter($instruments, fn($instrument) => $instrument !== 'vocals')
        );

        return static::query()->create([
            ...$attributes,
            'instruments' => $instrumentsWithoutVocals,
            'vocalist' => $isVocalist,
            'is_active' => $attributes['is_active'] ?? true
        ]);
    }

    public function getAllInstrumentsAttribute(): array
    {
        $instruments = $this->instruments ?? [];
        if ($this->vocalist) {
            array_unshift($instruments,'Vocals');
        }
        return $instruments;
    }


    /**
     * Define the relationship with bands through the band_musicians table.
     */
    public function bands()
    {
        return $this->belongsToMany(Band::class, 'band_musicians')
            ->using(BandMusician::class)
            ->withPivot('instrument', 'vocalist', 'match_metadata', 'match_score')
            ->withTimestamps();
    }
}
