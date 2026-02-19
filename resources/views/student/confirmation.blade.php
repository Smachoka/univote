@extends('layouts.app')

@section('title', 'Vote Confirmed')

@section('breadcrumb')
    <a href="{{ route('student.dashboard') }}" style="color:#c0392b;text-decoration:none;">Dashboard</a>
    &nbsp;/&nbsp; Confirmation
@endsection

@section('content')
<div style="max-width:540px;margin:0 auto;text-align:center;padding:20px 0;">

    <div style="width:80px;height:80px;background:#d4edda;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <i class="fa-solid fa-circle-check" style="font-size:2.2rem;color:#28a745;"></i>
    </div>

    <h1 style="font-size:1.6rem;font-weight:700;color:#333;margin:0 0 8px;">Vote Submitted!</h1>
    <p style="color:#777;margin:0 0 24px;font-size:0.9rem;">Your vote has been securely recorded.</p>

    <div style="background:white;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:20px;text-align:left;">
        <div style="display:flex;align-items:center;gap:8px;padding:14px 20px;border-bottom:1px solid #f0f0f0;background:#fafafa;">
            <div style="width:5px;height:18px;background:#c0392b;border-radius:2px;"></div>
            <h2 style="font-size:0.95rem;font-weight:600;color:#333;margin:0;">Voting Summary</h2>
        </div>
        <div style="padding:16px 20px;">
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f9f9f9;">
                <i class="fa-solid fa-user" style="color:#c0392b;width:16px;"></i>
                <div>
                    <p style="font-size:0.72rem;color:#aaa;margin:0;text-transform:uppercase;letter-spacing:0.05em;">Voter</p>
                    <p style="font-weight:600;color:#333;margin:0;font-size:0.875rem;">{{ auth()->user()->name }}</p>
                </div>
            </div>
            @if(auth()->user()->student_id)
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f9f9f9;">
                <i class="fa-solid fa-id-card" style="color:#c0392b;width:16px;"></i>
                <div>
                    <p style="font-size:0.72rem;color:#aaa;margin:0;text-transform:uppercase;letter-spacing:0.05em;">Student ID</p>
                    <p style="font-weight:600;color:#333;margin:0;font-size:0.875rem;">{{ auth()->user()->student_id }}</p>
                </div>
            </div>
            @endif
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f9f9f9;">
                <i class="fa-solid fa-box-ballot" style="color:#c0392b;width:16px;"></i>
                <div>
                    <p style="font-size:0.72rem;color:#aaa;margin:0;text-transform:uppercase;letter-spacing:0.05em;">Election</p>
                    <p style="font-weight:600;color:#333;margin:0;font-size:0.875rem;">{{ $election->title }}</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;">
                <i class="fa-regular fa-clock" style="color:#c0392b;width:16px;"></i>
                <div>
                    <p style="font-size:0.72rem;color:#aaa;margin:0;text-transform:uppercase;letter-spacing:0.05em;">Voted At</p>
                    <p style="font-weight:600;color:#333;margin:0;font-size:0.875rem;">{{ now()->format('M d, Y \a\t h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div style="background:#fef0f0;border:1px solid #f5c6cb;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:0.82rem;color:#721c24;">
        <i class="fa-solid fa-lock" style="margin-right:6px;"></i>
        Your vote is anonymous and securely stored. Results will be published once the election closes.
    </div>

    <a href="{{ route('student.dashboard') }}"
       style="background:#c0392b;color:white;padding:10px 28px;border-radius:8px;font-size:0.9rem;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
        <i class="fa-solid fa-house"></i> Back to Dashboard
    </a>
</div>
@endsection