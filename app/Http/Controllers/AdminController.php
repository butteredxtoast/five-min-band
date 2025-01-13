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
}
