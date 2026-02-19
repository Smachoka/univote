@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" style="color:#c0392b;text-decoration:none;">Dashboard</a>
@endsection

@section('content')

{{-- STAT CARDS --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px;">

    <div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:20px 24px;">
        <p style="font-size:0.8rem;color:#999;margin:0 0 4px;font-weight:500;text-transform:uppercase;letter-spacing:0.05em;">Total Elections</p>
        <p style="font-size:2rem;font-weight:700;color:#333;margin:0;">{{ $totalElections }}</p>
    </div>

    <div style="background:linear-gradient(135deg,#c0392b,#e74c3c);border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.12);padding:20px 24px;position:relative;overflow:hidden;">
        <p style="font-size:0.8rem;color:rgba(255,255,255,0.75);margin:0 0 4px;font-weight:500;text-transform:uppercase;letter-spacing:0.05em;">Active Elections</p>
        <p style="font-size:2rem;font-weight:700;color:white;margin:0;">{{ $activeElections }}</p>
        <i class="fa-solid fa-box-ballot" style="position:absolute;right:16px;top:50%;transform:translateY(-50%);font-size:3rem;color:rgba(255,255,255,0.15);"></i>
    </div>

    <div style="background:linear-gradient(135deg,#c0392b,#e74c3c);border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.12);padding:20px 24px;position:relative;overflow:hidden;">
        <p style="font-size:0.8rem;color:rgba(255,255,255,0.75);margin:0 0 4px;font-weight:500;text-transform:uppercase;letter-spacing:0.05em;">Students</p>
        <p style="font-size:2rem;font-weight:700;color:white;margin:0;">{{ $totalStudents }}</p>
        <i class="fa-solid fa-users" style="position:absolute;right:16px;top:50%;transform:translateY(-50%);font-size:3rem;color:rgba(255,255,255,0.15);"></i>
    </div>

    <div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:20px 24px;">
        <p style="font-size:0.8rem;color:#999;margin:0 0 4px;font-weight:500;text-transform:uppercase;letter-spacing:0.05em;">Total Votes Cast</p>
        <p style="font-size:2rem;font-weight:700;color:#c0392b;margin:0;">{{ $totalVotes }}</p>
    </div>

</div>

{{-- RECENT ELECTIONS TABLE CARD --}}
<div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:0;overflow:hidden;">

    {{-- Card Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f0f0f0;">
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:5px;height:20px;background:#c0392b;border-radius:2px;"></div>
            <h2 style="font-size:1rem;font-weight:600;color:#333;margin:0;">Recent Elections</h2>
        </div>
        <a href="{{ route('admin.elections.index') }}"
           style="background:#c0392b;color:white;padding:6px 14px;border-radius:5px;font-size:0.8rem;font-weight:600;text-decoration:none;">
            See all
        </a>
    </div>

    {{-- Table --}}
    <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
        <thead>
            <tr style="background:#fafafa;">
                <th style="padding:10px 20px;text-align:left;color:#999;font-weight:600;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.04em;border-bottom:1px solid #f0f0f0;">#</th>
                <th style="padding:10px 20px;text-align:left;color:#999;font-weight:600;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.04em;border-bottom:1px solid #f0f0f0;">Title</th>
                <th style="padding:10px 20px;text-align:left;color:#999;font-weight:600;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.04em;border-bottom:1px solid #f0f0f0;">Date Range</th>
                <th style="padding:10px 20px;text-align:left;color:#999;font-weight:600;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.04em;border-bottom:1px solid #f0f0f0;">Status</th>
                <th style="padding:10px 20px;text-align:left;color:#999;font-weight:600;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.04em;border-bottom:1px solid #f0f0f0;">Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($recentElections as $i => $election)
            <tr style="border-bottom:1px solid #f9f9f9;">
                <td style="padding:12px 20px;color:#aaa;">{{ $i + 1 }}</td>
                <td style="padding:12px 20px;font-weight:500;color:#333;">{{ $election->title }}</td>
                <td style="padding:12px 20px;color:#777;font-size:0.82rem;">
                    {{ $election->start_date->format('M d, Y') }} – {{ $election->end_date->format('M d, Y') }}
                </td>
                <td style="padding:12px 20px;">
                    @if($election->status === 'active')
                        <span style="background:#d4edda;color:#155724;padding:3px 10px;border-radius:50px;font-size:0.75rem;font-weight:600;">Active</span>
                    @elseif($election->status === 'closed')
                        <span style="background:#f8d7da;color:#721c24;padding:3px 10px;border-radius:50px;font-size:0.75rem;font-weight:600;">Closed</span>
                    @else
                        <span style="background:#e2e3e5;color:#383d41;padding:3px 10px;border-radius:50px;font-size:0.75rem;font-weight:600;">Draft</span>
                    @endif
                </td>
                <td style="padding:12px 20px;">
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.elections.positions.index', $election) }}"
                           title="Positions"
                           style="width:28px;height:28px;background:#fff3cd;border-radius:5px;display:flex;align-items:center;justify-content:center;color:#856404;text-decoration:none;font-size:0.8rem;">
                            <i class="fa-solid fa-list-ul"></i>
                        </a>
                        <a href="{{ route('admin.elections.results', $election) }}"
                           title="Results"
                           style="width:28px;height:28px;background:#d1ecf1;border-radius:5px;display:flex;align-items:center;justify-content:center;color:#0c5460;text-decoration:none;font-size:0.8rem;">
                            <i class="fa-solid fa-chart-column"></i>
                        </a>
                        <a href="{{ route('admin.elections.edit', $election) }}"
                           title="Edit"
                           style="width:28px;height:28px;background:#cce5ff;border-radius:5px;display:flex;align-items:center;justify-content:center;color:#004085;text-decoration:none;font-size:0.8rem;">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="padding:40px;text-align:center;color:#bbb;">
                    No elections yet. <a href="{{ route('admin.elections.create') }}" style="color:#c0392b;">Create one →</a>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- Quick Actions Footer --}}
    <div style="padding:14px 20px;background:#fafafa;border-top:1px solid #f0f0f0;display:flex;gap:10px;">
        <a href="{{ route('admin.elections.create') }}"
           style="background:#c0392b;color:white;padding:7px 16px;border-radius:5px;font-size:0.82rem;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:6px;">
            <i class="fa-solid fa-plus"></i> New Election
        </a>
        <a href="{{ route('admin.elections.index') }}"
           style="background:white;color:#555;border:1px solid #ddd;padding:7px 16px;border-radius:5px;font-size:0.82rem;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:6px;">
            <i class="fa-solid fa-list"></i> All Elections
        </a>
    </div>
</div>

@endsection