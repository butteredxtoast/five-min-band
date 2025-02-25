<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BandMusician extends Pivot
{
    protected $table = 'band_musicians';

    protected $casts = [
        'vocalist' => 'boolean',
        'match_metadata' => 'array',
        'match_score' => 'decimal:2'
    ];

    protected $fillable = [
        'instrument',
        'vocalist',
        'match_metadata',
        'match_score'
    ];

    /**
     * @throws Exception
     */
    public function validateAssignment(): bool
    {
        if ($this->vocalist && !$this->musician->vocalist) {
            throw new Exception("Musician cannot be assigned as vocalist");
        }

        if (!in_array($this->instrument, $this->musician->instruments)) {
            throw new Exception("Invalid instrument assignment");
        }

        return true;
    }

    public function band(): BelongsTo
    {
        return $this->belongsTo(Band::class);
    }

    public function musician(): BelongsTo
    {
        return $this->belongsTo(Musician::class);
    }
}
