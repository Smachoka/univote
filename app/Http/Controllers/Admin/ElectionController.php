<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::latest()->get();
        return view('admin.elections.index', compact('elections'));
    }

    public function create()
    {
        return view('admin.elections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        Election::create($validated);

        return redirect()->route('admin.elections.index')
            ->with('success', 'Election created successfully.');
    }

    public function edit(Election $election)
    {
        return view('admin.elections.edit', compact('election'));
    }

    public function update(Request $request, Election $election)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'status'      => 'required|in:upcoming,active,completed',
        ]);

        $election->update($validated);

        return redirect()->route('admin.elections.index')
            ->with('success', 'Election updated successfully.');
    }

    public function destroy(Election $election)
    {
        $election->delete();
        return redirect()->route('admin.elections.index')
            ->with('success', 'Election deleted.');
    }

    public function activate(Election $election)
    {
        // Only one election can be active at a time (optional strictness)
        $election->update(['status' => 'active']);
        return back()->with('success', "Election \"{$election->title}\" is now active.");
    }

    public function close(Election $election)
    {
        $election->update(['status' => 'closed']);
        return back()->with('success', "Election \"{$election->title}\" has been closed.");
    }
}