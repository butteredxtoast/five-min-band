<?php

namespace App\Http\Controllers;

use App\Models\Band;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Services\BandMatchingService;
use Illuminate\Support\Facades\Log;

class BandController extends Controller
{
    private BandMatchingService $matchingService;

    public function __construct(BandMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'musician_count' => ['required', 'integer', 'min:3', 'max:10'],
            'include_vocalist' => ['boolean'],
            'name' => 'nullable|string|max:255',
        ]);

        try {
            $band = $this->matchingService->generate(
                $validated['musician_count'],
                $request->boolean('include_vocalist', false),
                $validated['name'] ?? null
            );

            // Create a simplified version of the band data for session storage
            $simplifiedBand = [
                'id' => $band->id,
                'name' => $band->name,
                'musicians' => $band->musicians->map(function($musician) {
                    return [
                        'id' => $musician->id,
                        'name' => $musician->name,
                        'pivot' => [
                            'instrument' => $musician->pivot->instrument ?? null,
                            'vocalist' => $musician->pivot->vocalist ?? false
                        ]
                    ];
                })->toArray()
            ];

            return back()->with('success', 'Band generated successfully!')
                ->with('band_data', $simplifiedBand);

        } catch (Exception $e) {
            Log::error('Band generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', $e->getMessage());
        }
    }

    public function index(): Application
    {
        $bands = Band::with('musicians')->get();
        return view('admin.bands.index', compact('bands'));
    }
}
