<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Vote;

class ResultController extends Controller
{
    public function show(Election $election)
    {
        $results = [];

        foreach ($election->positions()->with('candidates')->get() as $position) {
            $candidates = $position->candidates->map(function ($candidate) {
                $candidate->vote_count = $candidate->votes()->count();
                return $candidate;
            })->sortByDesc('vote_count');

            $results[] = [
                'position'   => $position,
                'candidates' => $candidates,
                'winner'     => $candidates->first(),
                'total'      => $candidates->sum('vote_count'),
            ];
        }

        $totalVoters = Vote::where('election_id', $election->id)
            ->distinct('user_id')
            ->count('user_id');

        return view('admin.results.show', compact('election', 'results', 'totalVoters'));
    }
}