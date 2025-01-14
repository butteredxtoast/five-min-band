<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'participants' => ['required', 'integer', 'min:2', 'max:10'],
        ]);

        // Get all active participants
        $participants = Participant::where('is_active', true)->get();

        // If we don't have enough participants, return with an error
        if ($participants->count() < $request->participants) {
            return back()->with('error', 'Not enough active participants to generate a match.');
        }

        // Generate a single match
        $match = $this->generateMatch($participants, $request->participants);

        return back()->with('match', $match);
    }

    private function generateMatch($participants, $participantsPerMatch)
    {
        $participants = $participants->shuffle();
        return $participants->take($participantsPerMatch);
    }
}
