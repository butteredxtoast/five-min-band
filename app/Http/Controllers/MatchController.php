<?php

namespace App\Http\Controllers;

use App\Models\Musician;
use Illuminate\Http\Request;

class MatchController extends Controller
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
            return back()->with('error', 'Not enough active musicians to generate a match.');
        }

        // Generate a single match
        $match = $this->generateMatch($musicians, $request->musicians);

        return back()->with('match', $match);
    }

    private function generateMatch($musicians, $musiciansPerMatch)
    {
        $musicians = $musicians->shuffle();
        return $musicians->take($musiciansPerMatch);
    }
}
