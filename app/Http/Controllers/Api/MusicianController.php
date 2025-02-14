<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Musician;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MusicianController extends Controller
{
    private const VALID_INSTRUMENTS = ['vocals', 'guitar', 'bass', 'keys', 'other'];

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'instruments' => 'required|array',
            'instruments.*' => 'required|string|in:' . implode(',', self::VALID_INSTRUMENTS),
            'other' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $musician = Musician::create($validated);
        return response()->json($musician, 201);
    }

    public function random(): JsonResponse
    {
        $numInstruments = rand(1, 3);
        $randomInstruments = Arr::random(
            array_filter(self::VALID_INSTRUMENTS, fn($i) => $i !== 'other'),
            $numInstruments
        );

        $musician = Musician::create([
            'name' => fake()->name(),
            'instruments' => $randomInstruments,
            'other' => null,
            'is_active' => true
        ]);

        return response()->json($musician, 201);
    }
} 