<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
  public function create(Election $election, Position $position)
{
    return view('admin.elections.positions.candidates.create', compact('election', 'position'));
}

    public function store(Request $request, Election $election, Position $position)
{
    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string',
        'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')->store('candidates', 'public'); // ✅
    }

    $position->candidates()->create($validated);

    return redirect()->route('admin.elections.positions.index', $election)
        ->with('success', 'Candidate added.');
}

    public function destroy(Election $election, Position $position, Candidate $candidate)
    {
        $candidate->delete();
        return back()->with('success', 'Candidate removed.');
    }

    public function show(Election $election, Position $position, Candidate $candidate)
{
    return view('admin.elections.positions.candidates.show', compact('election', 'position', 'candidate'));
}
public function approve(Election $election, Position $position, Candidate $candidate)
{
    $candidate->update(['is_approved' => !$candidate->is_approved]);
    return back()->with('success', $candidate->is_approved ? 'Candidate approved.' : 'Candidate unapproved.');
}
}
