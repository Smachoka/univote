<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(Election $election)
    {
        $positions = $election->positions()->with('candidates')->get();
        return view('admin.positions.index', compact('election', 'positions'));
    }

    public function create(Election $election)
    {
        return view('admin.positions.create', compact('election'));
    }

    public function store(Request $request, Election $election)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $election->positions()->create(['name' => $request->name]);

        return redirect()->route('admin.elections.positions.index', $election)
            ->with('success', 'Position added.');
    }

    public function destroy(Election $election, Position $position)
    {
        $position->delete();
        return back()->with('success', 'Position deleted.');
    }
}