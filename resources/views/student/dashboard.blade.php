@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}</h1>
    <p class="text-gray-500 mt-1">
        @if(auth()->user()->student_id)
            Student ID: {{ auth()->user()->student_id }}
        @else
            Your voting portal
        @endif
    </p>
</div>

@if($activeElection)
    <div class="max-w-2xl">
        @if($hasVoted)
            {{-- Already Voted --}}
            <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-green-800 mb-2">You've Already Voted!</h2>
                <p class="text-green-700 text-sm mb-4">
                    Your vote for <strong>{{ $activeElection->title }}</strong> has been recorded.
                </p>
                <a href="{{ route('student.confirmation', $activeElection) }}"
                   class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold
                          px-5 py-2.5 rounded-lg text-sm transition">
                    View Confirmation
                </a>
            </div>
        @else
            {{-- Can Vote --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-indigo-600 px-6 py-5">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        <span class="text-indigo-200 text-xs font-medium uppercase tracking-wide">Active Election</span>
                    </div>
                    <h2 class="text-xl font-bold text-white">{{ $activeElection->title }}</h2>
                    @if($activeElection->description)
                        <p class="text-indigo-200 text-sm mt-1">{{ $activeElection->description }}</p>
                    @endif
                    <p class="text-indigo-300 text-xs mt-2">
                        {{ $activeElection->start_date->format('M d, Y') }} –
                        {{ $activeElection->end_date->format('M d, Y') }}
                    </p>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 text-sm mb-5">
                        This election is currently open. Cast your vote for each position.
                        <strong>You may only vote once.</strong>
                    </p>
                    <a href="{{ route('student.vote', $activeElection) }}"
                       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700
                              text-white font-semibold px-6 py-3 rounded-lg text-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                        Vote Now
                    </a>
                </div>
            </div>
        @endif
    </div>
@else
    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-lg font-semibold text-gray-700 mb-2">No Active Election</h2>
        <p class="text-gray-400 text-sm">
            There are no elections open for voting right now. Check back later.
        </p>
    </div>
@endif

@endsection