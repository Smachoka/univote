@extends('layouts.app')

@section('title', $candidate->name)

@section('breadcrumb')
    <a href="{{ route('admin.elections.index') }}" style="color:#c0392b;text-decoration:none;">Elections</a>
    &nbsp;/&nbsp; <a href="{{ route('admin.elections.positions.index', $election) }}" style="color:#c0392b;text-decoration:none;">Positions</a>
    &nbsp;/&nbsp; {{ $candidate->name }}
@endsection

@section('content')
<div style="max-width:620px;">
<div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);overflow:hidden;">

    <div style="display:flex;align-items:center;gap:8px;padding:16px 20px;border-bottom:1px solid #f0f0f0;">
        <div style="width:5px;height:18px;background:#c0392b;border-radius:2px;"></div>
        <h2 style="font-size:1rem;font-weight:600;color:#333;margin:0;">Candidate Profile</h2>
    </div>

    <div style="padding:28px 24px;display:flex;gap:20px;align-items:flex-start;">
        @if($candidate->photo)
            <img src="{{ Storage::url($candidate->photo) }}"
                 style="width:80px;height:80px;border-radius:50%;object-fit:cover;flex-shrink:0;border:3px solid #fef0f0;">
        @else
            <div style="width:80px;height:80px;border-radius:50%;background:#fef0f0;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.6rem;color:#c0392b;flex-shrink:0;">
                {{ strtoupper(substr($candidate->name, 0, 1)) }}
            </div>
        @endif

        <div style="flex:1;">
            <h3 style="font-size:1.2rem;font-weight:700;color:#222;margin:0 0 4px;">{{ $candidate->name }}</h3>
            <p style="font-size:0.82rem;color:#c0392b;font-weight:600;margin:0 0 12px;">
                <i class="fa-solid fa-sitemap"></i> {{ $position->name }}
            </p>
            <span style="background:{{ $candidate->is_approved ? '#d4edda' : '#fff3cd' }};color:{{ $candidate->is_approved ? '#155724' : '#856404' }};padding:3px 10px;border-radius:50px;font-size:0.75rem;font-weight:600;">
                {{ $candidate->is_approved ? 'Approved' : 'Pending Approval' }}
            </span>
        </div>
    </div>

    @if($candidate->description)
    <div style="padding:0 24px 20px;">
        <p style="font-size:0.8rem;font-weight:600;color:#999;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 6px;">Bio / Platform</p>
        <p style="font-size:0.9rem;color:#444;line-height:1.6;margin:0;">{{ $candidate->description }}</p>
    </div>
    @endif

    <div style="padding:16px 24px;border-top:1px solid #f0f0f0;display:flex;gap:8px;">
        <a href="{{ route('admin.elections.positions.index', $election) }}"
           style="background:#f0f0f0;color:#555;padding:8px 18px;border-radius:6px;font-size:0.85rem;font-weight:600;text-decoration:none;">
            ← Back
        </a>

        {{-- Approve / Unapprove toggle --}}
        <form method="POST" action="{{ route('admin.elections.positions.candidates.approve', [$election, $position, $candidate]) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    style="background:{{ $candidate->is_approved ? '#fff3cd' : '#d4edda' }};color:{{ $candidate->is_approved ? '#856404' : '#155724' }};padding:8px 18px;border-radius:6px;font-size:0.85rem;font-weight:600;border:none;cursor:pointer;">
                {{ $candidate->is_approved ? '✕ Unapprove' : '✓ Approve' }}
            </button>
        </form>

        <form method="POST" action="{{ route('admin.elections.positions.candidates.destroy', [$election, $position, $candidate]) }}"
              onsubmit="return confirm('Remove this candidate?')">
            @csrf @method('DELETE')
            <button type="submit"
                    style="background:#f8d7da;color:#721c24;padding:8px 18px;border-radius:6px;font-size:0.85rem;font-weight:600;border:none;cursor:pointer;">
                <i class="fa-solid fa-trash"></i> Remove
            </button>
        </form>
    </div>

</div>
</div>
@endsection