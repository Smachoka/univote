<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Only redirect admins away — students and guests see the homepage
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Get the active election with positions and approved candidates
        $activeElection = Election::where('status', 'active')
            ->with([
                'positions',
                'positions.candidates' => function ($query) {
                    $query->where('is_approved', true);
                },
            ])
            ->first();

        // Fallback: show the most recent election even if not active
        if (!$activeElection) {
            $activeElection = Election::with([
                'positions',
                'positions.candidates' => function ($query) {
                    $query->where('is_approved', true);
                },
            ])->latest()->first();
        }

        // Totals for stats strip
        $totalPositions = $activeElection
            ? $activeElection->positions->count()
            : 0;

        $totalCandidates = $activeElection
            ? $activeElection->positions->sum(fn ($p) => $p->candidates->count())
            : 0;

        $totalStudents = User::where('role', 'student')->count();

        $totalVotes = $activeElection
            ? Vote::where('election_id', $activeElection->id)->count()
            : 0;

        return view('home', compact(
            'activeElection',
            'totalCandidates',
            'totalPositions',
            'totalStudents',
            'totalVotes'
        ));
    }
}