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
    private const REQUIRED_INSTRUMENTS = ['guitar', 'drums', 'bass', 'keys', 'other'];

    /**
     * Generate a new band with matched musicians
     * @throws Exception
     */
    public function generate(int $musicianCount, bool $includeVocalist): Band
    {
        Log::info("catdog generate", [
            'musician count' => $musicianCount,
            'include vocalist' => $includeVocalist
        ]);

        $musicians = $this->getEligibleMusicians();

        if ($musicians->count() < $musicianCount) {
            throw new Exception("Not enough active musicians to generate a band.");
        }

        Log::info("catdog musician count", [
            'musician count' => $musicians->count()
        ]);

        $band = Band::create([
            'metadata' => [
                'generated_at' => now(),
                'musician_count' => $musicianCount,
                'vocalist_requested' => $includeVocalist
            ]
        ]);

        Log::info("catdog band", [
            'band' => $band
        ]);

        $this->assignRequiredInstruments($band, $musicians);

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
        $eligible = Musician::where('is_active', true)->get();
        Log::info("catdog eligible", [
            'eligible' => $eligible->map(function ($musician) {
                return [
                    'id' => $musician->id,
                    'name' => $musician->name,
                    'instruments' => json_encode($musician->instruments),
                    'vocalist' => $musician->vocalist,
                    'other' => $musician->other,
                    'is_active' => $musician->is_active,
                    'created_at' => $musician->created_at,
                    'updated_at' => $musician->updated_at,
                ];
            })->toArray()
        ]);
        return $eligible;
    }

    /**
     * Assign required instruments to the band
     * @throws Exception
     */
    private function assignRequiredInstruments(Band $band, Collection $musicians): void
    {
        Log::info("catdog assign required instruments", [
            'band' => $band,
            'musicians' => $musicians->map(function ($musician) {
                return [
                    'id' => $musician->id,
                    'name' => $musician->name,
                    'instruments' => json_encode($musician->instruments),
                    'vocalist' => $musician->vocalist,
                    'other' => $musician->other,
                    'is_active' => $musician->is_active,
                    'created_at' => $musician->created_at,
                    'updated_at' => $musician->updated_at,
                ];
            })->toArray()
        ]);

        foreach (self::REQUIRED_INSTRUMENTS as $instrument) {
            $eligibleMusicians = $musicians->filter(function ($musician) use ($instrument) {
                return in_array($instrument, $musician->instruments);
            });

            Log::info("assignRequired", [
                'instrument' => $instrument,
                'eligible_musicians' => $eligibleMusicians->map(fn($musician) => [
                    'id' => $musician->id,
                    'name' => $musician->name,
                    'instruments' => $musician->instruments
                ])->toArray()
            ]);

            if ($eligibleMusicians->isEmpty()) {
                throw new Exception("No available musicians can play $instrument");
            }

            $selectedMusician = $eligibleMusicians->random();

            $band->addMusician($selectedMusician, $instrument);

            $musicians = $musicians->reject(fn($m) => $m->id === $selectedMusician->id);
        }
    }

    /**
     * Assign a vocalist to the band
     * @throws Exception
     */
    private function assignVocalist(Band $band, Collection $musicians): void
    {
        try {
            $vocalists = $musicians->filter(fn($musician) => $musician->vocalist);

            if ($vocalists->isNotEmpty()) {
                $selectedVocalist = $vocalists->random();
                $band->addMusician($selectedVocalist, null, true);
            }
        } catch (Exception $e) {
            Log::warning('Failed to assign vocalist', [
                'band_id' => $band->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
