@extends('layouts.app')

@section('title', 'Elections')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" style="color:#c0392b;text-decoration:none;">Dashboard</a>
    &nbsp;/&nbsp; Elections
@endsection

@section('content')

<div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);overflow:hidden;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f0f0f0;flex-wrap:wrap;gap:10px;">
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:5px;height:20px;background:#c0392b;border-radius:2px;"></div>
            <h2 style="font-size:1rem;font-weight:600;color:#333;margin:0;">All Elections</h2>
        </div>
        <a href="{{ route('admin.elections.create') }}"
           style="background:#c0392b;color:white;padding:7px 16px;border-radius:5px;font-size:0.82rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
            <i class="fa-solid fa-plus"></i> Add New
        </a>
    </div>

    {{-- Table --}}
    <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
        <thead>
            <tr style="background:#fafafa;">
                <th style="padding:10px 20px;text-align:left;color:#999;font-weight:600;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.04em;border-bottom:1px solid #f0f0f0;width:36px;">
                    <input type="checkbox" style="accent-color:#c0392b;">
                </th>
                <th style="padding:10px 20px;text-align:left;color:#999;font-weight:600;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.04em;border-bottom:1px solid #f0f0f0;">#</th>
                <th style="padding:10px 20px;text-align:left;color:#999;font-weight:600;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.04em;border-bottom:1px solid #f0f0f0;">Title</th>
                <th style="padding:10px 20px;text-align:left;color:#999;font-weight:600;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.04em;border-bottom:1px solid #f0f0f0;">Date Range</th>
                <th style="padding:10px 20px;text-align:left;color:#999;font-weight:600;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.04em;border-bottom:1px solid #f0f0f0;">Status</th>
                <th style="padding:10px 20px;text-align:left;color:#999;font-weight:600;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.04em;border-bottom:1px solid #f0f0f0;">Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($elections as $i => $election)
            <tr style="border-bottom:1px solid #f9f9f9;">
                <td style="padding:12px 20px;">
                    <input type="checkbox" style="accent-color:#c0392b;">
                </td>
                <td style="padding:12px 20px;color:#aaa;font-size:0.82rem;">{{ $i + 1 }}</td>
                <td style="padding:12px 20px;">
                    <p style="font-weight:600;color:#333;margin:0;">{{ $election->title }}</p>
                    @if($election->description)
                        <p style="color:#999;font-size:0.78rem;margin:2px 0 0;">{{ Str::limit($election->description, 60) }}</p>
                    @endif
                </td>
                <td style="padding:12px 20px;color:#777;font-size:0.82rem;white-space:nowrap;">
                    {{ $election->start_date->format('M d, Y') }}<br>
                    <span style="color:#bbb;">→ {{ $election->end_date->format('M d, Y') }}</span>
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
                    <div style="display:flex;gap:5px;flex-wrap:wrap;">

                        {{-- Positions --}}
                        <a href="{{ route('admin.elections.positions.index', $election) }}"
                           title="Positions"
                           style="width:30px;height:30px;background:#fef9e7;border-radius:5px;display:inline-flex;align-items:center;justify-content:center;color:#c0392b;text-decoration:none;font-size:0.82rem;">
                            <i class="fa-solid fa-list-ul"></i>
                        </a>

                        {{-- Results --}}
                        <a href="{{ route('admin.elections.results', $election) }}"
                           title="Results"
                           style="width:30px;height:30px;background:#fef9e7;border-radius:5px;display:inline-flex;align-items:center;justify-content:center;color:#c0392b;text-decoration:none;font-size:0.82rem;">
                            <i class="fa-solid fa-chart-column"></i>
                        </a>

                        {{-- Edit --}}
                        <a href="{{ route('admin.elections.edit', $election) }}"
                           title="Edit"
                           style="width:30px;height:30px;background:#fef9e7;border-radius:5px;display:inline-flex;align-items:center;justify-content:center;color:#c0392b;text-decoration:none;font-size:0.82rem;">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        {{-- Activate --}}
                        @if($election->isDraft() || $election->isClosed())
                        <form method="POST" action="{{ route('admin.elections.activate', $election) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" title="Activate"
                                    style="width:30px;height:30px;background:#d4edda;border-radius:5px;display:inline-flex;align-items:center;justify-content:center;color:#155724;border:none;cursor:pointer;font-size:0.82rem;">
                                <i class="fa-solid fa-play"></i>
                            </button>
                        </form>
                        @endif

                        {{-- Close --}}
                        @if($election->isActive())
                        <form method="POST" action="{{ route('admin.elections.close', $election) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" title="Close"
                                    style="width:30px;height:30px;background:#f8d7da;border-radius:5px;display:inline-flex;align-items:center;justify-content:center;color:#721c24;border:none;cursor:pointer;font-size:0.82rem;">
                                <i class="fa-solid fa-stop"></i>
                            </button>
                        </form>
                        @endif

                        {{-- Delete --}}
                        <form method="POST" action="{{ route('admin.elections.destroy', $election) }}" style="display:inline;"
                              onsubmit="return confirm('Delete this election?')">
                            @csrf @method('DELETE')
                            <button type="submit" title="Delete"
                                    style="width:30px;height:30px;background:#f8d7da;border-radius:5px;display:inline-flex;align-items:center;justify-content:center;color:#721c24;border:none;cursor:pointer;font-size:0.82rem;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="padding:48px;text-align:center;color:#bbb;">
                    <i class="fa-solid fa-box-open" style="font-size:2rem;margin-bottom:8px;display:block;"></i>
                    No elections found.
                    <a href="{{ route('admin.elections.create') }}" style="color:#c0392b;">Create your first →</a>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    </div>

    <div style="padding:10px 20px;background:#fafafa;border-top:1px solid #f0f0f0;font-size:0.78rem;color:#999;">
        Showing {{ $elections->count() }} {{ Str::plural('election', $elections->count()) }}
    </div>
</div>

@endsection