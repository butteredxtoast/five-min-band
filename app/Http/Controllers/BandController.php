<?php

namespace App\Http\Controllers;

use App\Models\Band;
use App\Models\Musician;
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
        Log::info("catdog inbound request", [
            'request' => $request
        ]);

        $request->validate([
            'musician_count' => ['required', 'integer', 'min:3', 'max:10'],
            'include_vocalist' => ['boolean'],
        ]);

        try {
            $band = $this->matchingService->generate(
                $request['musician_count'],
                $request->boolean('include_vocalist', true)
            );

            return back()->with('success', 'Band generated successfully!')
                ->with('band', $band->load('musicians'));

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function index(): Application
    {
        $bands = Band::with('musicians')->get();
        return view('admin.bands.index', compact('bands'));
    }
}
