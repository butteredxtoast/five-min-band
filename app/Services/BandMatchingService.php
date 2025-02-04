<?php

namespace App\Services;

use App\Models\Band;
use App\Models\Musician;
use Illuminate\Support\Collection;

class BandMatchingService
{
    /**
    * Required instruments for a complete band
    */
    private const REQUIRED_INSTRUMENTS = ['guitar', 'drums', 'bass', 'keys', 'other'];

    /**
     * Generate a new band with matched musicians
     */
    public function generate(int $musicianCount, bool $includeVocalist = true): Band
    {
        $musicians = $this->getEligibleMusicians();

        if ($musicians->count() < $musicianCount) {
            throw new \Exception("Not enough active musicians to generate a band.");
        }

        // Create the band
        $band = Band::create([
            'metadata' => [
                'generated_at' => now(),
                'musician_count' => $musicianCount,
                'vocalist_requested' => $includeVocalist
            ]
        ]);

        // Assign instruments based on requirements
        $this->assignRequiredInstruments($band, $musicians);

        // Optionally assign a vocalist
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
     * Assign required instruments to the band
     */
    private function assignRequiredInstruments(Band $band, Collection $musicians): void
    {
        foreach (self::REQUIRED_INSTRUMENTS as $instrument) {
            // Find musicians who can play this instrument
            $eligibleMusicians = $musicians->filter(function ($musician) use ($instrument) {
                return in_array($instrument, $musician->instruments);
            });

            if ($eligibleMusicians->isEmpty()) {
                throw new \Exception("No available musicians can play $instrument");
            }

            // Assign a random eligible musician to the band
            $selectedMusician = $eligibleMusicians->random();
            
            // Add the musician to the band
            $band->addMusician($selectedMusician, $instrument);

            // Remove the musician from the eligible pool
            $musicians = $musicians->reject(fn($m) => $m->id === $selectedMusician->id);
        }
    }

    /**
     * Assign a vocalist to the band
     */
    private function assignVocalist(Band $band, Collection $musicians): void
    {
        // Find a vocalist who can play any instrument
        $vocalists = $musicians->filter(fn($musician) => $musician->vocalist);

        if ($vocalists->isNotEmpty()) {
            $selectedVocalist = $vocalists->random();
            $band->addMusician($selectedVocalist, null, true);
        }
    }
}
