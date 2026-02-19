@extends('layouts.app')

@section('title', 'Positions')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" style="color:#c0392b;text-decoration:none;">Dashboard</a>
    &nbsp;/&nbsp; <a href="{{ route('admin.elections.index') }}" style="color:#c0392b;text-decoration:none;">Elections</a>
    &nbsp;/&nbsp; {{ Str::limit($election->title, 30) }} &nbsp;/&nbsp; Positions
@endsection

@section('content')

{{-- Add Position --}}
<div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:20px;margin-bottom:20px;">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
        <div style="width:5px;height:18px;background:#c0392b;border-radius:2px;"></div>
        <h2 style="font-size:0.95rem;font-weight:600;color:#333;margin:0;">Add Position to: <em style="color:#c0392b;">{{ $election->title }}</em></h2>
    </div>
    <form method="POST" action="{{ route('admin.elections.positions.store', $election) }}"
          style="display:flex;gap:10px;align-items:flex-start;">
        @csrf
        <div style="flex:1;">
            <input type="text" name="name" placeholder="e.g., President, Secretary, Treasurer..."
                   style="width:100%;padding:9px 14px;border:1px solid #ddd;border-radius:6px;font-size:0.875rem;outline:none;box-sizing:border-box;"
                   onfocus="this.style.borderColor='#c0392b'" onblur="this.style.borderColor='#ddd'"
                   required>
            @error('name')<p style="color:#c0392b;font-size:0.78rem;margin:4px 0 0;">{{ $message }}</p>@enderror
        </div>
        <button type="submit"
                style="background:#c0392b;color:white;padding:9px 18px;border-radius:6px;font-size:0.85rem;font-weight:600;border:none;cursor:pointer;white-space:nowrap;">
            <i class="fa-solid fa-plus"></i> Add Position
        </button>
    </form>
</div>

{{-- Positions + Candidates --}}
@forelse($positions as $position)
<div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);margin-bottom:16px;overflow:hidden;">

    {{-- Position Row --}}
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:#fafafa;border-bottom:1px solid #f0f0f0;">
        <div style="display:flex;align-items:center;gap:8px;">
            <i class="fa-solid fa-sitemap" style="color:#c0392b;"></i>
            <h3 style="font-size:0.95rem;font-weight:700;color:#333;margin:0;">{{ $position->name }}</h3>
            <span style="background:#f0f0f0;color:#777;padding:2px 8px;border-radius:50px;font-size:0.72rem;">
                {{ $position->candidates->count() }} {{ Str::plural('candidate', $position->candidates->count()) }}
            </span>
        </div>
        <div style="display:flex;gap:6px;">
            <a href="{{ route('admin.elections.positions.candidates.create', [$election, $position]) }}"
               style="background:#c0392b;color:white;padding:6px 14px;border-radius:5px;font-size:0.8rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                <i class="fa-solid fa-user-plus"></i> Add Candidate
            </a>
            <form method="POST" action="{{ route('admin.elections.positions.destroy', [$election, $position]) }}"
                  onsubmit="return confirm('Delete this position and all candidates?')">
                @csrf @method('DELETE')
                <button type="submit"
                        style="background:#f8d7da;color:#721c24;padding:6px 12px;border-radius:5px;font-size:0.8rem;font-weight:600;border:none;cursor:pointer;">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Candidates Table --}}
    <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
        @forelse($position->candidates as $candidate)
<tr style="border-bottom:1px solid #f9f9f9;">
    <td style="padding:10px 20px;width:44px;">
        @if($candidate->photo)
            <img src="{{ Storage::url($candidate->photo) }}"
                 style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
        @else
            <div style="width:36px;height:36px;border-radius:50%;background:#fef0f0;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem;color:#c0392b;">
                {{ strtoupper(substr($candidate->name, 0, 1)) }}
            </div>
        @endif
    </td>
    <td style="padding:10px 20px;">
        <p style="font-weight:600;color:#333;margin:0;">{{ $candidate->name }}</p>
        @if($candidate->description)
            <p style="color:#999;font-size:0.78rem;margin:2px 0 0;">{{ Str::limit($candidate->description, 70) }}</p>
        @endif
    </td>
    <td style="padding:10px 20px;text-align:right;">
        <div style="display:flex;gap:6px;justify-content:flex-end;align-items:center;">
            <a href="{{ route('admin.elections.positions.candidates.show', [$election, $position, $candidate]) }}"
               style="background:#eaf0fb;color:#2c5282;padding:5px 10px;border-radius:5px;font-size:0.78rem;font-weight:600;text-decoration:none;">
                <i class="fa-solid fa-eye"></i> View
            </a>
            <form method="POST" action="{{ route('admin.elections.positions.candidates.destroy', [$election, $position, $candidate]) }}"
                  onsubmit="return confirm('Remove candidate?')">
                @csrf @method('DELETE')
                <button type="submit"
                        style="background:#f8d7da;color:#721c24;padding:5px 10px;border-radius:5px;font-size:0.78rem;border:none;cursor:pointer;">
                    <i class="fa-solid fa-trash"></i> Remove
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
        <tr>
            <td colspan="3" style="padding:20px;text-align:center;color:#bbb;font-size:0.82rem;">
                No candidates yet. <a href="{{ route('admin.elections.positions.candidates.create', [$election, $position]) }}" style="color:#c0392b;">Add one →</a>
            </td>
        </tr>
        @endforelse
    </table>
</div>
@empty
<div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:48px;text-align:center;color:#bbb;">
    <i class="fa-solid fa-sitemap" style="font-size:2rem;margin-bottom:8px;display:block;"></i>
    No positions yet. Use the form above to add one.
</div>
@endforelse

@endsection