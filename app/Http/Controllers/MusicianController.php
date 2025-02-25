<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMusicianRequest;
use App\Models\Musician;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class MusicianController extends Controller
{
    public function create(): View
    {
        return view('musicians.create', [
            'instruments' => ['Vocals', 'Guitar', 'Bass', 'Drums', 'Keys', 'Other']
        ]);
    }

    public function store(StoreMusicianRequest $request): JsonResponse
    {
        try {
            $musician = Musician::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Musician created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create musician'
            ], 500);
        }
    }
}
