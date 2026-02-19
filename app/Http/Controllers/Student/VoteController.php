<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function dashboard()
    {
        $activeElection = Election::where('status', 'active')->first();
        $hasVoted = false;

        if ($activeElection) {
            $hasVoted = auth()->user()->hasVotedInElection($activeElection->id);
        }

        return view('student.dashboard', compact('activeElection', 'hasVoted'));
    }

    public function show(Election $election)
    {
        if (!$election->isActive()) {
            return redirect()->route('student.dashboard')
                ->with('error', 'This election is not currently active.');
        }

        if (auth()->user()->hasVotedInElection($election->id)) {
            return redirect()->route('student.confirmation', $election)
                ->with('info', 'You have already voted in this election.');
        }

        $positions = $election->positions()->with('candidates')->get();

        if ($positions->isEmpty()) {
            return redirect()->route('student.dashboard')
                ->with('error', 'This election has no positions set up yet.');
        }

        return view('student.vote', compact('election', 'positions'));
    }

  public function store(Request $request, Election $election)
{
    // Guard checks
    if (!$election->isActive()) {
        return redirect()->route('student.dashboard')
            ->with('error', 'This election is not active.');
    }

    if (auth()->user()->hasVotedInElection($election->id)) {
        return redirect()->route('student.confirmation', $election)
            ->with('info', 'You have already voted.');
    }

    $positions = $election->positions()->with('candidates')->get();

    // Validate that a candidate was chosen for each position
    $rules = [];
    $messages = [];
    
    foreach ($positions as $position) {
        $candidateIds = $position->candidates->pluck('id')->toArray();
        
        // Make sure there are candidates for this position
        if (empty($candidateIds)) {
            return redirect()->back()
                ->with('error', "No candidates available for position: {$position->name}");
        }
        
        $rules["votes.{$position->id}"] = [
            'required',
            'integer',
            'in:' . implode(',', $candidateIds),
        ];
        
        $messages["votes.{$position->id}.required"] = "Please select a candidate for {$position->name}.";
        $messages["votes.{$position->id}.in"] = "Selected candidate for {$position->name} is invalid.";
    }

    $request->validate($rules, $messages);

    // Insert one vote per position - FIXED: ADDED election_id
    foreach ($positions as $position) {
        Vote::create([
            'election_id'  => $election->id,  // THIS WAS MISSING
            'position_id'  => $position->id,
            'candidate_id' => $request->input("votes.{$position->id}"),
            'user_id'      => auth()->id(),
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
        ]);
    }

    return redirect()->route('student.confirmation', $election)
        ->with('success', 'Your vote has been recorded. Thank you!');
}

    public function confirmation(Election $election)
    {
        // Verify the user has actually voted in this election
        if (!auth()->user()->hasVotedInElection($election->id)) {
            return redirect()->route('student.dashboard')
                ->with('error', 'You have not voted in this election yet.');
        }
        
        return view('student.confirmation', compact('election'));
    }
}