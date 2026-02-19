@extends('layouts.app')

@section('title', 'Vote')

@section('breadcrumb')
    <a href="{{ route('student.dashboard') }}" style="color:#c0392b;text-decoration:none;">Dashboard</a>
    &nbsp;/&nbsp; Vote
@endsection

@section('content')

<div style="max-width:700px;">

    {{-- Notice --}}
    <div style="background:#fff3cd;border:1px solid #ffeeba;border-radius:8px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:0.85rem;color:#856404;">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span><strong>Important:</strong> You can only vote once. Select a candidate for every position before submitting.</span>
    </div>

    <form method="POST" action="{{ route('student.vote.store', $election) }}">
        @csrf

        @foreach($positions as $position)
        <div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);margin-bottom:16px;overflow:hidden;">

            {{-- Position Header --}}
            <div style="display:flex;align-items:center;gap:8px;padding:14px 20px;background:#fafafa;border-bottom:1px solid #f0f0f0;">
                <div style="width:5px;height:18px;background:#c0392b;border-radius:2px;"></div>
                <h2 style="font-size:0.95rem;font-weight:700;color:#333;margin:0;">{{ $position->name }}</h2>
                <span style="font-size:0.72rem;color:#bbb;margin-left:4px;">— select one</span>
            </div>

            {{-- Candidates --}}
            <div style="padding:16px 20px;display:flex;flex-direction:column;gap:10px;">
                @forelse($position->candidates as $candidate)
                <label style="display:flex;align-items:center;gap:14px;padding:12px 16px;border:2px solid #f0f0f0;border-radius:8px;cursor:pointer;transition:border-color 0.15s;"
                       onmouseover="this.style.borderColor='#c0392b'" onmouseout="checkBorder(this)">

                    <input type="radio"
                           name="votes[{{ $position->id }}]"
                           value="{{ $candidate->id }}"
                           required
                           style="accent-color:#c0392b;width:16px;height:16px;flex-shrink:0;"
                           onchange="highlightLabel(this)">

                    @if($candidate->photo)
                        <img src="{{ Storage::url($candidate->photo) }}"
                             style="width:40px;height:40px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                    @else
                        <div style="width:40px;height:40px;border-radius:50%;background:#fef0f0;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.95rem;color:#c0392b;">
                            {{ strtoupper(substr($candidate->name, 0, 1)) }}
                        </div>
                    @endif

                    <div style="flex:1;">
                        <p style="font-weight:600;color:#333;margin:0;">{{ $candidate->name }}</p>
                        @if($candidate->bio)
                            <p style="color:#999;font-size:0.78rem;margin:2px 0 0;">{{ Str::limit($candidate->bio, 90) }}</p>
                        @endif
                    </div>

                    <i class="fa-solid fa-circle-check" style="color:#c0392b;font-size:1.1rem;opacity:0;" class="check-icon"></i>
                </label>
                @empty
                    <p style="color:#bbb;font-size:0.82rem;">No candidates for this position.</p>
                @endforelse
            </div>
        </div>
        @endforeach

        {{-- Submit --}}
        <div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:20px;">
            <p style="font-size:0.82rem;color:#999;margin:0 0 14px;">
                By clicking submit, you confirm your selections. <strong>This cannot be undone.</strong>
            </p>
            <button type="submit"
                    style="width:100%;background:#c0392b;color:white;padding:12px;border:none;border-radius:8px;font-size:1rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                <i class="fa-solid fa-paper-plane"></i> Submit My Vote
            </button>
        </div>
    </form>
</div>

<script>
function checkBorder(label) {
    const radio = label.querySelector('input[type=radio]');
    if (!radio.checked) label.style.borderColor = '#f0f0f0';
}
function highlightLabel(radio) {
    // Reset all in same group
    document.querySelectorAll('input[name="' + radio.name + '"]').forEach(r => {
        r.closest('label').style.borderColor = '#f0f0f0';
        r.closest('label').querySelector('.fa-circle-check').style.opacity = '0';
    });
    radio.closest('label').style.borderColor = '#c0392b';
    radio.closest('label').querySelector('.fa-circle-check').style.opacity = '1';
}
</script>
@endsection