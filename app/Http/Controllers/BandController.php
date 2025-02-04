<?php

namespace App\Http\Controllers;

use App\Models\Musician;
use Illuminate\Http\Request;

class BandController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'musicians' => ['required', 'integer', 'min:2', 'max:10'],
        ]);

        // Get all active musicians
        $musicians = Musician::where('is_active', true)->get();

        // If we don't have enough musicians, return with an error
        if ($musicians->count() < $request->musicians) {
            return back()->with('error', 'Not enough active musicians to generate a band.');
        }

        // Generate a single band
        $band = $this->generateBand($musicians, $request->musicians);

        return back()->with('band', $band);
    }

    private function generateBand($musicians, $musiciansPerBand)
    {
        $musicians = $musicians->shuffle();
        return $musicians->take($musiciansPerBand);
    }
}
