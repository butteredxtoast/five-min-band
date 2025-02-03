<?php

namespace App\Http\Controllers;

use App\Models\Musician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MusicianController extends Controller
{
    public function store(Request $request)
    {
        Log::info($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'instruments' => 'required|array',
            'instruments.*' => 'string|in:Vocals,Guitar,Bass,Drums,Keys,Other',
            'other' => 'nullable|string|max:255'
        ]);

        $musician = Musician::create([
            'name' => $validated['name'],
            'instruments' => $validated['instruments'],
            'other' => $validated['other'],
            'is_active' => true
        ]);

        return redirect()->route('welcome')->with('success', 'Thank you for signing up!');
    }
}
