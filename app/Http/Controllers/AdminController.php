<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Participant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        $users = User::all();
        $participants = Participant::all();
        
        return view('admin.dashboard', compact('users', 'participants'));
    }

    /**
     * Show the users list.
     */
    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the participants list.
     */
    public function participants()
    {
        $participants = Participant::all();
        return view('admin.participants.index', compact('participants'));
    }

    /**
     * Update the specified user.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users')->with('status', 'user-updated');
    }

    /**
     * Update the specified participant.
     */
    public function updateParticipant(Request $request, Participant $participant)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'instruments' => ['required', 'array'],
            'instruments.*' => ['string'],
            'other' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $participant->update($validated);

        return redirect()->route('admin.participants')->with('status', 'participant-updated');
    }

    /**
     * Bulk update participants.
     */
    public function bulkUpdateParticipants(Request $request)
    {
        $validated = $request->validate([
            'participant_ids' => ['required', 'array'],
            'participant_ids.*' => ['exists:participants,id'],
            'is_active' => ['required', 'boolean'],
        ]);

        Participant::whereIn('id', $validated['participant_ids'])
            ->update(['is_active' => $validated['is_active']]);

        $action = $validated['is_active'] ? 'activated' : 'deactivated';
        return redirect()->route('admin.participants')
            ->with('status', "participants-{$action}");
    }
}
