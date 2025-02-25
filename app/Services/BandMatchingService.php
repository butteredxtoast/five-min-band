<?php

namespace App\Services;

use App\Models\Band;
use App\Models\Musician;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class BandMatchingService
{
    /**
    * Required instruments for a complete band
    */
    private const PRIORITIZED_INSTRUMENTS = ['guitar', 'bass', 'drums', 'keys', 'other'];

    /**
     * Generate a new band with matched musicians
     * @throws Exception
     */
    public function generate(int $musicianCount, bool $includeVocalist): Band
    {
        $musicians = $this->getEligibleMusicians();

        $requiredTotal = $includeVocalist ? $musicianCount + 1 : $musicianCount;

        if ($musicians->count() < $requiredTotal) {
            throw new Exception("Not enough active musicians to generate a band.");
        }

        $band = Band::create([
            'name' => 'New Band ' . now()->format('Y-m-d'),
            'status' => 'active',
            'metadata' => [
                'generated_at' => now(),
                'musician_count' => $musicianCount,
                'vocalist_requested' => $includeVocalist
            ]
        ]);

        $this->assignInstrumentalists($band, $musicians, $musicianCount);

        if ($includeVocalist) {
            $this->assignVocalist($band, $musicians);
        }

        return $band->fresh(['musicians']);
    }

    /**
    * Get all eligible musicians for band formation
    */
    private function getEligibleMusicians(): Collection
    {
        return Musician::where('is_active', true)->get();
    }

    /**
     * Assign instrumentalists to the band based on count
     * @throws Exception
     */
    private function assignInstrumentalists(Band $band, Collection &$musicians, int $count): void
    {
        // The available instrument types we'll randomly select from
        $availableInstrumentTypes = self::PRIORITIZED_INSTRUMENTS;

        Log::info("Assigning instrumentalists", [
            'band_id' => $band->id,
            'count' => $count
        ]);

        // Assign the requested number of instrumentalists
        for ($i = 0; $i < $count; $i++) {
            // Randomly select an instrument type for this position
            $instrument = $availableInstrumentTypes[array_rand($availableInstrumentTypes)];

            // Find musicians who can play this instrument
            $eligibleMusicians = $musicians->filter(function ($musician) use ($instrument) {
                return in_array($instrument, $musician->instruments);
            });

            if ($eligibleMusicians->isEmpty()) {
                // If no one plays this specific instrument, find someone with any instrument skill
                $eligibleMusicians = $musicians->filter(function ($musician) {
                    return !empty($musician->instruments);
                });

                if ($eligibleMusicians->isEmpty()) {
                    throw new Exception("Not enough musicians with required skills");
                }

                // Select a random musician and use their first instrument
                $selectedMusician = $eligibleMusicians->random();
                $assignedInstrument = !empty($selectedMusician->instruments)
                    ? $selectedMusician->instruments[0]
                    : 'other';
            } else {
                // Select a random eligible musician for this instrument
                $selectedMusician = $eligibleMusicians->random();
                $assignedInstrument = $instrument;
            }

            // Add them to the band
            $band->addMusician($selectedMusician, $assignedInstrument);

            // Remove from the available pool
            $musicians = $musicians->reject(fn($m) => $m->id === $selectedMusician->id);
        }
    }

    /**
     * Assign a vocalist to the band
     * @throws Exception
     */
    private function assignVocalist(Band $band, Collection &$musicians): void
    {
        try {
            $vocalists = $musicians->filter(fn($musician) => $musician->vocalist);

            if ($vocalists->isNotEmpty()) {
                $selectedVocalist = $vocalists->random();
                $band->addMusician($selectedVocalist, null, true);

                $musicians = $musicians->reject(fn($m) => $m->id === $selectedVocalist->id);
            } else {
                Log::warning('No vocalists available to assign', [
                    'band_id' => $band->id
                ]);
            }
        } catch (Exception $e) {
            Log::warning('Failed to assign vocalist', [
                'band_id' => $band->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
