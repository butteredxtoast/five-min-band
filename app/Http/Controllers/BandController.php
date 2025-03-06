<?php

namespace App\Http\Controllers;

use App\Models\Band;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Services\BandMatchingService;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class BandController extends Controller
{
    private BandMatchingService $matchingService;

    public function __construct(BandMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    public function generate(Request $request)
    {
        $validBandTypes = array_merge(['random'], array_keys(BandMatchingService::BAND_TYPES));

        $validated = $request->validate([
            'musician_count' => ['integer', 'min:2', 'max:10', 'required_if:band_type,random'],
            'include_vocalist' => ['boolean'],
            'name' => 'nullable|string|max:255',
            'band_type' => ['required', 'string', Rule::in($validBandTypes)]
        ]);

        try {
            $options = [
                'name' => $validated['name'] ?? null,
            ];

            // Handle based on band type selection
            if ($validated['band_type'] === 'random') {
                $options['musician_count'] = $validated['musician_count'];
                $options['include_vocalist'] = $request->boolean('include_vocalist', false);
                $options['band_type'] = null; // Ensure no band type is set for random mode
            } else {
                $options['band_type'] = $validated['band_type'];
            }

            $band = $this->matchingService->generate($options);

            return back()->with('success', 'Band generated successfully!')
                ->with('band', $band->load('musicians'));

        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function index(): Application
    {
        $bands = Band::with('musicians')->get();
        return view('admin.bands.index', compact('bands'));
    }
}
