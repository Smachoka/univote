@extends('layouts.app')

@section('title', 'Positions - ' . $election->title)

@section('breadcrumb')
    <a href="{{ route('admin.elections.index') }}" style="color:#c0392b;text-decoration:none;">Elections</a>
    &nbsp;/&nbsp; {{ $election->title }} Positions
@endsection

@section('content')
<div style="max-width:900px;">
    {{-- Header with Election Info --}}
    <div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f0f0f0;">
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:5px;height:18px;background:#c0392b;border-radius:2px;"></div>
                <h2 style="font-size:1rem;font-weight:600;color:#333;margin:0;">{{ $election->title }} - Positions</h2>
            </div>
            <span style="background:{{ $election->status == 'active' ? '#d4edda' : ($election->status == 'completed' ? '#fff3cd' : '#e2e3e5') }};color:{{ $election->status == 'active' ? '#155724' : ($election->status == 'completed' ? '#856404' : '#383d41') }};padding:4px 12px;border-radius:50px;font-size:0.75rem;font-weight:600;">
                {{ ucfirst($election->status) }}
            </span>
        </div>
        <div style="padding:16px 20px;display:flex;gap:20px;background:#fafafa;">
            <div style="font-size:0.85rem;">
                <span style="color:#666;">Start:</span> 
                <span style="font-weight:600;">{{ $election->start_date->format('M d, Y') }}</span>
            </div>
            <div style="font-size:0.85rem;">
                <span style="color:#666;">End:</span> 
                <span style="font-weight:600;">{{ $election->end_date->format('M d, Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Positions List --}}
    @forelse($positions as $position)
    <div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:20px;">
        {{-- Position Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f0f0f0;">
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:5px;height:18px;background:#c0392b;border-radius:2px;"></div>
                <h3 style="font-size:1rem;font-weight:600;color:#333;margin:0;">{{ $position->name }}</h3>
                <span style="background:#f0f0f0;color:#666;padding:2px 8px;border-radius:50px;font-size:0.7rem;font-weight:600;">
                    {{ $position->candidates->count() }} {{ Str::plural('candidate', $position->candidates->count()) }}
                </span>
            </div>
            <div style="display:flex;gap:8px;">
                <a href="{{ route('admin.elections.positions.candidates.create', [$election, $position]) }}"
                   style="background:#c0392b;color:white;padding:6px 14px;border-radius:6px;font-size:0.8rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                    <i class="fa-solid fa-plus"></i> Add Candidate
                </a>
                <form method="POST" action="{{ route('admin.elections.positions.destroy', [$election, $position]) }}"
                      onsubmit="return confirm('Delete this position and all its candidates?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            style="background:#f8d7da;color:#721c24;padding:6px 14px;border-radius:6px;font-size:0.8rem;font-weight:600;border:none;cursor:pointer;">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Candidates Grid --}}
        <div style="padding:20px;">
            @if($position->candidates->count() > 0)
                <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:16px;">
                    @foreach($position->candidates as $candidate)
                        <div style="background:#fafafa;border-radius:8px;border:1px solid #f0f0f0;overflow:hidden;">
                            {{-- Candidate Card --}}
                            <div style="padding:16px;display:flex;align-items:center;gap:12px;">
                                {{-- Avatar --}}
                                @if($candidate->photo)
                                    <img src="{{ Storage::url($candidate->photo) }}"
                                         style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid #fff;box-shadow:0 2px 4px rgba(0,0,0,0.05);">
                                @else
                                    <div style="width:48px;height:48px;border-radius:50%;background:#fef0f0;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.2rem;color:#c0392b;">
                                        {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                    </div>
                                @endif

                                {{-- Info --}}
                                <div style="flex:1;min-width:0;">
                                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;">
                                        <h4 style="font-size:0.9rem;font-weight:600;color:#333;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $candidate->name }}
                                        </h4>
                                        <span style="background:{{ $candidate->is_approved ? '#d4edda' : '#fff3cd' }};color:{{ $candidate->is_approved ? '#155724' : '#856404' }};padding:2px 6px;border-radius:50px;font-size:0.65rem;font-weight:600;white-space:nowrap;">
                                            {{ $candidate->is_approved ? '✓' : '⏳' }}
                                        </span>
                                    </div>
                                    @if($candidate->description)
                                        <p style="font-size:0.75rem;color:#666;margin:0 0 6px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                            {{ $candidate->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div style="padding:8px 16px;border-top:1px solid #f0f0f0;background:white;display:flex;gap:8px;justify-content:flex-end;">
                                <a href="{{ route('admin.elections.positions.candidates.show', [$election, $position, $candidate]) }}"
                                   style="color:#c0392b;font-size:0.75rem;font-weight:600;text-decoration:none;padding:4px 8px;border-radius:4px;">
                                    View
                                </a>
                                <form method="POST" action="{{ route('admin.elections.positions.candidates.destroy', [$election, $position, $candidate]) }}"
                                      onsubmit="return confirm('Remove this candidate?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="color:#721c24;font-size:0.75rem;font-weight:600;background:none;border:none;cursor:pointer;padding:4px 8px;border-radius:4px;">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center;padding:40px 20px;background:#fafafa;border-radius:8px;">
                    <div style="font-size:2rem;margin-bottom:10px;">📋</div>
                    <p style="color:#666;margin-bottom:16px;">No candidates added for this position yet.</p>
                    <a href="{{ route('admin.elections.positions.candidates.create', [$election, $position]) }}"
                       style="background:#c0392b;color:white;padding:8px 20px;border-radius:6px;font-size:0.85rem;font-weight:600;text-decoration:none;display:inline-block;">
                        + Add First Candidate
                    </a>
                </div>
            @endif
        </div>
    </div>
    @empty
        <div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:60px 20px;text-align:center;">
            <div style="font-size:3rem;margin-bottom:15px;">🗳️</div>
            <h3 style="font-size:1.1rem;color:#333;margin-bottom:8px;">No Positions Created</h3>
            <p style="color:#666;margin-bottom:20px;">Get started by creating your first position for this election.</p>
            <a href="{{ route('admin.elections.positions.create', $election) }}"
               style="background:#c0392b;color:white;padding:10px 24px;border-radius:6px;font-size:0.9rem;font-weight:600;text-decoration:none;display:inline-block;">
                + Create Position
            </a>
        </div>
    @endforelse

    {{-- Add Position Button (if positions exist) --}}
    @if($positions->count() > 0)
    <div style="margin-top:20px;text-align:center;">
        <a href="{{ route('admin.elections.positions.create', $election) }}"
           style="background:#f0f0f0;color:#333;padding:10px 24px;border-radius:6px;font-size:0.9rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
            <i class="fa-solid fa-plus"></i> Add Another Position
        </a>
    </div>
    @endif
</div>
@endsection