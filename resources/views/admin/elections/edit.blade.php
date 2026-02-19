@extends('layouts.app')

@section('title', 'Edit Election')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" style="color:#c0392b;text-decoration:none;">Dashboard</a>
    &nbsp;/&nbsp; <a href="{{ route('admin.elections.index') }}" style="color:#c0392b;text-decoration:none;">Elections</a>
    &nbsp;/&nbsp; Edit
@endsection

@section('content')
<div style="max-width:680px;">
<div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);overflow:hidden;">
    <div style="display:flex;align-items:center;gap:8px;padding:16px 20px;border-bottom:1px solid #f0f0f0;">
        <div style="width:5px;height:18px;background:#c0392b;border-radius:2px;"></div>
        <h2 style="font-size:1rem;font-weight:600;color:#333;margin:0;">Edit Election</h2>
    </div>
    <div style="padding:24px;">
        <form method="POST" action="{{ route('admin.elections.update', $election) }}">
            @csrf @method('PUT')
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.82rem;font-weight:600;color:#555;margin-bottom:6px;">Election Title *</label>
                <input type="text" name="title" value="{{ old('title', $election->title) }}"
                       style="width:100%;padding:9px 14px;border:1px solid #ddd;border-radius:6px;font-size:0.875rem;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#c0392b'" onblur="this.style.borderColor='#ddd'"
                       required>
                @error('title')<p style="color:#c0392b;font-size:0.78rem;margin:4px 0 0;">{{ $message }}</p>@enderror
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.82rem;font-weight:600;color:#555;margin-bottom:6px;">Description</label>
                <textarea name="description" rows="3"
                          style="width:100%;padding:9px 14px;border:1px solid #ddd;border-radius:6px;font-size:0.875rem;outline:none;resize:vertical;box-sizing:border-box;"
                          onfocus="this.style.borderColor='#c0392b'" onblur="this.style.borderColor='#ddd'">{{ old('description', $election->description) }}</textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:600;color:#555;margin-bottom:6px;">Start Date *</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $election->start_date->format('Y-m-d')) }}"
                           style="width:100%;padding:9px 14px;border:1px solid #ddd;border-radius:6px;font-size:0.875rem;outline:none;box-sizing:border-box;" required>
                </div>
                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:600;color:#555;margin-bottom:6px;">End Date *</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $election->end_date->format('Y-m-d')) }}"
                           style="width:100%;padding:9px 14px;border:1px solid #ddd;border-radius:6px;font-size:0.875rem;outline:none;box-sizing:border-box;" required>
                </div>
            </div>
            
            {{-- Status Field --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:0.82rem;font-weight:600;color:#555;margin-bottom:6px;">Status</label>
                <select name="status"
                        style="width:100%;padding:9px 14px;border:1px solid #ddd;border-radius:6px;font-size:0.875rem;outline:none;box-sizing:border-box;">
                    <option value="upcoming"  {{ old('status', $election->status) == 'upcoming'  ? 'selected' : '' }}>Upcoming</option>
                    <option value="active"    {{ old('status', $election->status) == 'active'    ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ old('status', $election->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')<p style="color:#c0392b;font-size:0.78rem;margin:4px 0 0;">{{ $message }}</p>@enderror
            </div>

            <div style="display:flex;align-items:center;gap:10px;padding-top:16px;border-top:1px solid #f0f0f0;">
                <button type="submit"
                        style="background:#c0392b;color:white;padding:9px 22px;border-radius:6px;font-size:0.85rem;font-weight:600;border:none;cursor:pointer;">
                    Update Election
                </button>
                <a href="{{ route('admin.elections.index') }}"
                   style="color:#777;font-size:0.85rem;text-decoration:none;">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection