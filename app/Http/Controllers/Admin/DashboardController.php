<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\User;
use App\Models\Vote;

class DashboardController extends Controller
{
    public function index()
    {
        // Get active election (for the status card)
        $activeElection = Election::where('status', 'active')->first();
        
        // Admin is not supposed to vote, so always false
        $hasVoted = false;
        
        // Statistics
        $totalElections = Election::count();
        $activeElections = Election::where('status', 'active')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalVotes = Vote::count();
        
        // Recent elections for the list
        $recentElections = Election::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'activeElection',
            'hasVoted',
            'totalElections',
            'activeElections',
            'totalStudents',
            'totalVotes',
            'recentElections'
        ));
    }
}