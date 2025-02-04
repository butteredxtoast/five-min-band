<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BandMusician extends Pivot
{
    protected $casts = [
        'vocalist' => 'boolean',
        'match_metadata' => 'array',
        'match_score' => 'decimal:2'
    ];
}