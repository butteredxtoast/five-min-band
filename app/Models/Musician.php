<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Musician extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'instruments',
        'other',
        'vocalist',
        'is_active'
    ];

    protected $casts = [
        'instruments' => 'array',
        'vocalist' => 'boolean',
        'is_active' => 'boolean'
    ];

    private mixed $vocalist;

    public static function create(array $attributes = [])
    {
        $isVocalist = false;

        if (isset($attributes['instruments']) && in_array('Vocals', $attributes['instruments'])) {
            $instruments = array_map('strtolower', $attributes['instruments']);

            $isVocalist = in_array('vocals', $instruments);

            $attributes['instruments'] = array_values(
                array_filter($instruments, fn($instrument) => $instrument !== 'vocals')
            );
        }

        $attributes['vocalist'] = $isVocalist;
        $attributes['is_active'] = $attributes['is_active'] ?? true;

        return static::query()->create($attributes);
    }

    public function getAllInstrumentsAttribute(): array
    {
        $instruments = $this->instruments ?? [];
        if ($this->vocalist) {
            array_unshift($instruments,'Vocals');
        }
        return $instruments;
    }
}
