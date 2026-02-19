@extends('layouts.app')

@section('title', 'Results')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" style="color:#c0392b;text-decoration:none;">Dashboard</a>
    &nbsp;/&nbsp; <a href="{{ route('admin.elections.index') }}" style="color:#c0392b;text-decoration:none;">Elections</a>
    &nbsp;/&nbsp; Results
@endsection

@section('content')

{{-- Summary bar --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:20px;">
    <div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:16px 20px;">
        <p style="font-size:0.75rem;color:#999;margin:0 0 4px;text-transform:uppercase;letter-spacing:0.05em;">Election</p>
        <p style="font-size:0.95rem;font-weight:700;color:#333;margin:0;">{{ Str::limit($election->title, 28) }}</p>
    </div>
    <div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:16px 20px;">
        <p style="font-size:0.75rem;color:#999;margin:0 0 4px;text-transform:uppercase;letter-spacing:0.05em;">Total Voters</p>
        <p style="font-size:1.6rem;font-weight:700;color:#c0392b;margin:0;">{{ $totalVoters }}</p>
    </div>
    <div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:16px 20px;">
        <p style="font-size:0.75rem;color:#999;margin:0 0 4px;text-transform:uppercase;letter-spacing:0.05em;">Positions</p>
        <p style="font-size:1.6rem;font-weight:700;color:#333;margin:0;">{{ count($results) }}</p>
    </div>
    <div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:16px 20px;">
        <p style="font-size:0.75rem;color:#999;margin:0 0 4px;text-transform:uppercase;letter-spacing:0.05em;">Status</p>
        @if($election->status === 'active')
            <span style="background:#d4edda;color:#155724;padding:4px 12px;border-radius:50px;font-size:0.8rem;font-weight:600;">Active</span>
        @elseif($election->status === 'closed')
            <span style="background:#f8d7da;color:#721c24;padding:4px 12px;border-radius:50px;font-size:0.8rem;font-weight:600;">Closed</span>
        @else
            <span style="background:#e2e3e5;color:#383d41;padding:4px 12px;border-radius:50px;font-size:0.8rem;font-weight:600;">Draft</span>
        @endif
    </div>
</div>

{{-- Per-position results --}}
@forelse($results as $result)
<div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);margin-bottom:16px;overflow:hidden;">

    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:#fef9f9;border-bottom:1px solid #f0f0f0;">
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:5px;height:18px;background:#c0392b;border-radius:2px;"></div>
            <h2 style="font-size:1rem;font-weight:700;color:#333;margin:0;">{{ $result['position']->name }}</h2>
        </div>
        <span style="font-size:0.8rem;color:#999;">{{ $result['total'] }} votes cast</span>
    </div>

    <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
        @foreach($result['candidates'] as $candidate)
        @php
            $isWinner = $result['winner'] && $candidate->id === $result['winner']->id && $result['total'] > 0;
            $pct = $result['total'] > 0 ? round(($candidate->vote_count / $result['total']) * 100, 1) : 0;
        @endphp
        <tr style="border-bottom:1px solid #f9f9f9;{{ $isWinner ? 'background:#fff8f8;' : '' }}">
            <td style="padding:12px 20px;width:44px;">
                @if($candidate->photo)
                    <img src="{{ Storage::url($candidate->photo) }}"
                         style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                @else
                    <div style="width:36px;height:36px;border-radius:50%;background:{{ $isWinner ? '#c0392b' : '#f0f0f0' }};display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem;color:{{ $isWinner ? 'white' : '#999' }};">
                        {{ strtoupper(substr($candidate->name, 0, 1)) }}
                    </div>
                @endif
            </td>
            <td style="padding:12px 20px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                    <p style="font-weight:{{ $isWinner ? '700' : '500' }};color:#333;margin:0;">{{ $candidate->name }}</p>
                    @if($isWinner)
                        <span style="background:#c0392b;color:white;padding:2px 8px;border-radius:50px;font-size:0.7rem;font-weight:600;">🏆 Winner</span>
                    @endif
                </div>
                <div style="background:#f0f0f0;border-radius:50px;height:7px;width:100%;max-width:300px;">
                    <div style="width:{{ $pct }}%;height:7px;border-radius:50px;background:{{ $isWinner ? '#c0392b' : '#ccc' }};transition:width 0.4s;"></div>
                </div>
            </td>
            <td style="padding:12px 20px;text-align:right;white-space:nowrap;">
                <span style="font-weight:700;color:{{ $isWinner ? '#c0392b' : '#555' }};font-size:1rem;">{{ $candidate->vote_count }}</span>
                <span style="color:#bbb;font-size:0.8rem;margin-left:4px;">({{ $pct }}%)</span>
            </td>
        </tr>
        @endforeach

        @if($result['total'] === 0)
        <tr>
            <td colspan="3" style="padding:20px;text-align:center;color:#bbb;font-size:0.82rem;">No votes yet.</td>
        </tr>
        @endif
    </table>
</div>
@empty
<div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:48px;text-align:center;color:#bbb;">
    No results available yet.
</div>
@endforelse

@endsection