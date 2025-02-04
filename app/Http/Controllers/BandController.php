<?php

namespace App\Http\Controllers;

use App\Models\Musician;
use Illuminate\Http\Request;
use App\Services\BandMatchingService;

class BandController extends Controller
{
    private BandMatchingService $matchingService;

    public function __construct(BandMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    public function generate(Request $request)
    {
        $request->validate([
            'musicians' => ['required', 'integer', 'min:3', 'max:10'],
            'include_vocalist' => ['boolean'],
        ]);

        try {
            $band = $this->matchingService->generate(
                $request->musicians,
                $request->boolean('include_vocalist', true)
            );

            return back()->with('success', 'Band generated successfully!')
                        ->with('band', $band);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
