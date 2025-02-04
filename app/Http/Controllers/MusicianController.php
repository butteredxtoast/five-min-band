<?php

namespace App\Http\Controllers;

use App\Models\Musician;
use Illuminate\Http\Request;

class MusicianController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'instruments' => 'required|array',
            'instruments.*' => 'string|in:Vocals,Guitar,Bass,Drums,Keys,Other',
            'other' => 'nullable|string|max:255'
        ]);

        // Filter out 'Vocals' from instruments array as it will be handled by the vocalist boolean
        $instruments = array_filter($validated['instruments'], fn($instrument) => $instrument !== 'Vocals');
        
        $musician = Musician::create([
            'name' => $validated['name'],
            'instruments' => array_values($instruments), // Reindex array after filtering
            'other' => $validated['other'],
            'is_active' => true,
            'vocalist' => in_array('Vocals', $validated['instruments'])
        ]);

        return redirect()->route('welcome')->with('success', 'Thank you for signing up!');
    }
}
