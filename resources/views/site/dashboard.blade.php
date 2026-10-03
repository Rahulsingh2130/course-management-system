@extends('layouts.site')

@section('title', 'My Dashboard')

@php
    $statusStyle = [
        'confirmed' => 'bg-green-100 text-green-700',
        'pending' => 'bg-amber-100 text-amber-700',
        'cancelled' => 'bg-slate-100 text-slate-500',
    ];
@endphp

@section('content')
<div class="max-w-5xl mx-auto px-4 mt-10">
    <h1 class="text-3xl font-extrabold">Hello, {{ auth()->user()->name }}</h1>
    <p class="text-slate-600 mt-1">Manage your course enrolments here.</p>

    <h2 class="text-xl font-extrabold mt-10 mb-4">Upcoming courses</h2>
    <div class="space-y-3">
        @forelse ($upcoming as $e)
            <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <a href="{{ route('course', $e->workshop->course->slug) }}" class="font-bold hover:text-brand-600">{{ $e->workshop->course->title }}</a>
                    <div class="text-sm text-slate-500">{{ $e->workshop->starts_at->format('D, d M Y, h:i A') }} · {{ $e->workshop->modeLabel() }} · {{ $e->workshop->location }}</div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusStyle[$e->status] }}">{{ ucfirst($e->status) }}</span>
                    <form method="post" action="{{ route('enrollments.cancel', $e) }}" onsubmit="return confirm('Cancel this enrolment?')">@csrf @method('DELETE')
                        <button class="text-sm text-red-600 hover:underline">Cancel</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-slate-50 border border-dashed border-slate-300 rounded-xl p-8 text-center text-slate-600">
                You have no upcoming courses. <a href="{{ route('courses') }}" class="text-brand-600 font-semibold">Browse courses →</a>
            </div>
        @endforelse
    </div>

    @if ($past->isNotEmpty())
        <h2 class="text-xl font-extrabold mt-10 mb-4">History</h2>
        <div class="space-y-3">
            @foreach ($past as $e)
                <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-wrap items-center justify-between gap-4 opacity-80">
                    <div>
                        <div class="font-bold">{{ $e->workshop->course->title }}</div>
                        <div class="text-sm text-slate-500">{{ $e->workshop->starts_at->format('d M Y') }} · {{ $e->workshop->modeLabel() }}</div>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusStyle[$e->status] }}">{{ ucfirst($e->status) }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
