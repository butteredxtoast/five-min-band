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
     * Predefined band types with their specific instrument requirements
     */
    public const BAND_TYPES = [
        'punk' => [
            'instruments' => ['drums', 'guitar', 'bass'],
            'vocalist_from' => ['guitar', 'bass']
        ],
        'rock' => [
            'instruments' => ['drums', 'guitar', 'bass', 'keys'],
            'vocalist_from' => ['guitar', 'bass', 'keys']
        ],
        'indie' => [
            'instruments' => ['drums', 'guitar', 'bass', 'keys', 'other'],
            'vocalist_from' => ['guitar', 'keys']
        ],
        'jazz' => [
            'instruments' => ['drums', 'bass', 'keys', 'other'],
            'vocalist_from' => null
        ],
        'electronic' => [
            'instruments' => ['keys', 'other'],
            'vocalist_from' => ['keys']
        ],
    ];

    /**
     * Main entry point for band generation
     *
     * @param array $options Configuration options for band generation
     * @return Band
     * @throws Exception
     */
    public function generate(array $options = []): Band
    {
        $options = array_merge([
            'musician_count' => null,
            'include_vocalist' => false,
            'name' => null,
            'band_type' => null,
        ], $options);

        $availableMusicians = $this->getEligibleMusicians();

        $band = $this->createBandEntity($options);

        if ($options['band_type'] && isset(self::BAND_TYPES[$options['band_type']])) {
            $this->applyBandTypeStrategy($band, $availableMusicians, $options['band_type']);
        } else {
            $this->applyCustomStrategy($band, $availableMusicians, $options);
        }

        return $band->fresh(['musicians']);
    }

    /**
     * Create the base band entity
     */
    private function createBandEntity(array $options): Band
    {
        $metadata = [
            'generated_at' => now(),
        ];

        // Add relevant metadata based on generation strategy
        if ($options['band_type']) {
            $metadata['band_type'] = $options['band_type'];
        } else {
            $metadata['musician_count'] = $options['musician_count'];
            $metadata['vocalist_requested'] = $options['include_vocalist'];
        }

        return Band::create([
            'name' => $options['name'],
            'status' => 'active',
            'metadata' => $metadata
        ]);
    }

    /**
     * Apply strategy for predefined band types
     * @throws Exception
     */
    private function applyBandTypeStrategy(Band $band, Collection $availableMusicians, string $bandType): void
    {
        $bandConfig = self::BAND_TYPES[$bandType];
        $requiredInstruments = $bandConfig['instruments'];
        $vocalistFromInstruments = $bandConfig['vocalist_from'];

        if ($availableMusicians->count() < count($requiredInstruments)) {
            throw new Exception("Not enough active musicians to generate a {$bandType} band.");
        }

        $remainingMusicians = clone $availableMusicians;

        foreach ($requiredInstruments as $instrument) {
            $this->assignInstrumentalist($band, $remainingMusicians, $instrument);
        }

        if ($vocalistFromInstruments === null) {
            return;
        } else if (!empty($vocalistFromInstruments)) {
            $this->assignVocalistFromInstrumentalists($band, $vocalistFromInstruments);
        } else {
            $this->assignDedicatedVocalist($band, $remainingMusicians);
        }
    }

    /**
     * Apply strategy for custom band configuration
     * @throws Exception
     */
    private function applyCustomStrategy(Band $band, Collection $availableMusicians, array $options): void
    {
        $musicianCount = $options['musician_count'];
        $includeVocalist = $options['include_vocalist'];

        $requiredTotal = $includeVocalist ? $musicianCount + 1 : $musicianCount;

        if ($availableMusicians->count() < $requiredTotal) {
            throw new Exception("Not enough active musicians to generate a band.");
        }

        $remainingMusicians = clone $availableMusicians;

        $this->assignRandomInstrumentalists($band, $remainingMusicians, $musicianCount);

        if ($includeVocalist) {
            $this->assignDedicatedVocalist($band, $remainingMusicians);
        }
    }

    /**
     * Assign a musician for a specific instrument
     * @throws Exception
     */
    private function assignInstrumentalist(Band $band, Collection &$remainingMusicians, string $instrument): void
    {
        $eligibleMusicians = $remainingMusicians->filter(function ($musician) use ($instrument) {
            return in_array($instrument, $musician->instruments);
        });

        if ($eligibleMusicians->isEmpty()) {
            throw new Exception("Cannot find musician for {$instrument}.");
        }

        $selectedMusician = $eligibleMusicians->random();
        $band->addMusician($selectedMusician, $instrument);

        $remainingMusicians = $remainingMusicians->reject(fn($m) => $m->id === $selectedMusician->id);
    }

    /**
     * Assign random instrumentalists based on count
     * @throws Exception
     */
    private function assignRandomInstrumentalists(Band $band, Collection &$remainingMusicians, int $count): void
    {
        $availableInstrumentTypes = self::PRIORITIZED_INSTRUMENTS;

        for ($i = 0; $i < $count; $i++) {
            $instrument = $availableInstrumentTypes[array_rand($availableInstrumentTypes)];

            $eligibleMusicians = $remainingMusicians->filter(function ($musician) use ($instrument) {
                return in_array($instrument, $musician->instruments);
            });

            if ($eligibleMusicians->isEmpty()) {
                $eligibleMusicians = $remainingMusicians->filter(function ($musician) {
                    return !empty($musician->instruments);
                });

                if ($eligibleMusicians->isEmpty()) {
                    throw new Exception("Not enough musicians with required skills");
                }

                $selectedMusician = $eligibleMusicians->random();
                $assignedInstrument = !empty($selectedMusician->instruments)
                    ? $selectedMusician->instruments[0]
                    : 'other';
            } else {
                $selectedMusician = $eligibleMusicians->random();
                $assignedInstrument = $instrument;
            }

            $band->addMusician($selectedMusician, $assignedInstrument);

            $remainingMusicians = $remainingMusicians->reject(fn($m) => $m->id === $selectedMusician->id);
        }
    }

    /**
     * Make an existing band member also serve as vocalist
     */
    private function assignVocalistFromInstrumentalists(Band $band, array $eligibleInstruments): void
    {
        try {
            $bandMusicianIds = $band->musicians->pluck('id')->toArray();

            if (empty($bandMusicianIds)) {
                Log::warning('No musicians in band to assign as vocalist', ['band_id' => $band->id]);
                return;
            }

            // Get the band_musicians pivot records directly without using the relationship
            $query = \DB::table('band_musicians')
                ->where('band_id', $band->id);

            if (!empty($eligibleInstruments)) {
                $query->whereIn('instrument', $eligibleInstruments);
            }

            $eligibleBandMembers = $query->get();

            $canSingMusicianIds = Musician::whereIn('id', $eligibleBandMembers->pluck('musician_id'))
                ->where('vocalist', true)
                ->pluck('id')
                ->toArray();

            $eligibleVocalists = $eligibleBandMembers->filter(function($member) use ($canSingMusicianIds) {
                return in_array($member->musician_id, $canSingMusicianIds);
            });

            if ($eligibleVocalists->isNotEmpty()) {
                $selectedMember = $eligibleVocalists->random();

                // Update directly via DB query to avoid any relationship issues
                \DB::table('band_musicians')
                    ->where('band_id', $band->id)
                    ->where('musician_id', $selectedMember->musician_id)
                    ->update(['vocalist' => true]);

                Log::info('Assigned vocalist from instrumentalists', [
                    'band_id' => $band->id,
                    'musician_id' => $selectedMember->musician_id
                ]);
            } else {
                Log::warning('No eligible instrumentalists can sing in this band', [
                    'band_id' => $band->id,
                    'eligible_instruments' => $eligibleInstruments
                ]);
            }
        } catch (Exception $e) {
            Log::error('Error assigning vocalist from instrumentalists', [
                'band_id' => $band->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Assign a dedicated vocalist to the band
     */
    private function assignDedicatedVocalist(Band $band, Collection &$remainingMusicians): void
    {
        try {
            $vocalists = $remainingMusicians->filter(fn($musician) => $musician->vocalist);

            if ($vocalists->isNotEmpty()) {
                $selectedVocalist = $vocalists->random();
                $band->addMusician($selectedVocalist, null, true);

                $remainingMusicians = $remainingMusicians->reject(fn($m) => $m->id === $selectedVocalist->id);
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

    /**
     * Get all eligible musicians for band formation
     */
    private function getEligibleMusicians(): Collection
    {
        return Musician::where('is_active', true)
            ->whereNotIn('id', function($query) {
                $query->select('musician_id')
                    ->from('band_musicians')
                    ->join('bands', 'bands.id', '=', 'band_musicians.band_id')
                    ->where('bands.status', 'active');
            })
            ->get();
    }
}
