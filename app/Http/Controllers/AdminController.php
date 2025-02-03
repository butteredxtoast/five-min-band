<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Musician;
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
        $musicians = Musician::all();
        
        return view('admin.dashboard', compact('users', 'musicians'));
    }

    /**
     * Show the matches management page.
     */
    public function matches()
    {
        return view('admin.matches');
    }

    /**
     * Show the users management page.
     */
    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the musicians list.
     */
    public function musicians()
    {
        $musicians = Musician::all();
        $availableInstruments = [
            'Guitar',
            'Bass',
            'Drums',
            'Vocals',
            'Keys',
            'Other'
        ];
        return view('admin.musicians.index', compact('musicians', 'availableInstruments'));
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
     * Update the specified musician.
     */
    public function updateMusician(Request $request, Musician $musician)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'instruments' => ['required', 'array'],
            'instruments.*' => ['string'],
            'other' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $musician->update($validated);

        return redirect()->route('admin.musicians')->with('status', 'musician-updated');
    }

    /**
     * Bulk update musicians.
     */
    public function bulkUpdateMusicians(Request $request)
    {
        $validated = $request->validate([
            'musician_ids' => ['required', 'array'],
            'musician_ids.*' => ['exists:musicians,id'],
            'is_active' => ['required', 'boolean'],
        ]);

        Musician::whereIn('id', $validated['musician_ids'])
            ->update(['is_active' => $validated['is_active']]);

        $action = $validated['is_active'] ? 'activated' : 'deactivated';
        return redirect()->route('admin.musicians')
            ->with('status', "musicians-{$action}");
    }

    /**
     * Bulk activate musicians.
     */
    public function bulkActivateMusicians(Request $request)
    {
        $musicians = $request->input('musicians', []);
        Musician::whereIn('id', $musicians)->update(['is_active' => true]);

        return back()->with('status', 'musicians-activated');
    }

    /**
     * Bulk deactivate musicians.
     */
    public function bulkDeactivateMusicians(Request $request)
    {
        $musicians = $request->input('musicians', []);
        Musician::whereIn('id', $musicians)->update(['is_active' => false]);

        return back()->with('status', 'musicians-deactivated');
    }
}
