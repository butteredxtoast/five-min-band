<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'instruments' => 'required|array',
            'instruments.*' => 'string|in:Vocals,Guitar,Bass,Drums,Keys,Other',
            'other' => 'nullable|string|max:255'
        ]);

        $participant = Participant::create([
            'name' => $validated['name'],
            'instruments' => $validated['instruments'],
            'other' => $validated['other'],
            'is_active' => true
        ]);

        return redirect()->route('welcome')->with('success', 'Thank you for signing up!');
    }
}
