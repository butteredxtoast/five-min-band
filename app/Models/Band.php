<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;

class Band extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
        'played',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'played' => 'boolean'
    ];

    /**
     * Get the musicians in this band
     */
    public function musicians(): BelongsToMany
    {
        return $this->belongsToMany(Musician::class, 'band_musicians')
        ->using(BandMusician::class)
        ->withPivot('instrument', 'vocalist', 'match_metadata', 'match_score')
        ->withTimestamps();
    }

    /**
     * Get only the vocalist for this band
     */
    public function vocalist()
    {
        return $this->musicians()
            ->wherePivot('vocalist', true)
            ->first();
    }

    /**
     * Assign a musician to an instrument in the band
     *
     * @param Musician $musician
     * @param string $instrument
     * @param bool $asVocalist
     *
     * @throws Exception
     *
     */
    public function addMusician(Musician $musician, ?string $instrument, bool $asVocalist = false): void
    {
        if ($instrument !== null && !in_array($instrument, $musician->instruments)) {
            throw new Exception("Instrument $instrument is not valid for musician " . $musician->name);
        }

        if ($asVocalist && !$musician->vocalist) {
            throw new Exception("Musician is not a vocalist");
        }

        $this->musicians()->attach($musician->id, [
            'instrument' => $instrument,
            'vocalist' => $asVocalist,
            'match_metadata' => json_encode([
                'assigned_at' => now(),
                'assigned_instrument' => $instrument,
                'assigned_as_vocalist' => $asVocalist
            ]),
        ]);
    }

    /**
     * Check if band has all required instruments
     */
    public function isComplete(): bool
    {
        $requiredInstruments = ['Guitar', 'Bass', 'Drums', 'Keys'];
        $assignedInstruments = $this->musicians()
            ->wherePivot('instrument', '!=', null)
            ->pluck('instrument')
            ->toArray();

        return empty(array_diff($requiredInstruments, $assignedInstruments));
    }

    /**
     * Remove a musician from the band
     *
     * @param Musician $musician
     *
     */

    public function removeMusician(Musician $musician): void
    {
        $this->musicians()->detach([$musician->id]);
    }
}
