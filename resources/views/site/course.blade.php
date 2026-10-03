@extends('layouts.site')

@section('title', $course->title . ' Course & Dates')
@section('description', $course->short_description)

@php
    $user = auth()->user();
    $formProps = json_encode([
        'type' => 'course',
        'courseId' => $course->id,
        'message' => true,
        'submitLabel' => 'Request a call back',
        'user' => $user ? ['name' => $user->name, 'email' => $user->email] : new stdClass,
    ]);
@endphp

@section('content')
<section class="bg-gradient-to-br from-brand-900 to-brand-700 text-white">
    <div class="max-w-7xl mx-auto px-4 py-12 grid lg:grid-cols-3 gap-10 items-center">
        <div class="lg:col-span-2">
            <nav class="text-sm text-brand-200"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('courses') }}">Courses</a> / <a href="{{ route('category', $course->category) }}">{{ $course->category->name }}</a></nav>
            <h1 class="text-3xl lg:text-5xl font-extrabold mt-3">{{ $course->title }}</h1>
            <p class="mt-4 text-lg text-brand-100 max-w-2xl">{{ $course->short_description }}</p>
            <div class="mt-6 flex flex-wrap gap-x-6 gap-y-2 text-sm">
                <span>⏱ {{ $course->duration_days }} {{ Str::plural('day', $course->duration_days) }}</span>
                <span>📶 {{ $course->level }}</span>
                <span class="text-amber-300 font-semibold">★ {{ $course->rating }} rated</span>
                <span>🎓 Certification prep</span>
            </div>
        </div>
        <div class="bg-white text-slate-800 rounded-2xl p-6 shadow-2xl">
            <div class="text-sm text-slate-500">Course fee from</div>
            <div class="text-3xl font-extrabold">₹{{ number_format($course->price) }} <span class="text-sm font-medium text-slate-500">+ GST</span></div>
            <a href="#batches" class="mt-4 block text-center bg-accent-500 hover:bg-accent-600 text-white font-bold py-3 rounded-lg">Choose a batch</a>
            <a href="#enquire" class="mt-2 block text-center border border-brand-600 text-brand-700 font-semibold py-3 rounded-lg hover:bg-brand-50">Enquire now</a>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 mt-10 grid lg:grid-cols-3 gap-10">
    <div class="lg:col-span-2 space-y-12">
        <section>
            <h2 class="text-2xl font-extrabold">Overview</h2>
            <p class="mt-3 text-slate-700 leading-relaxed">{{ $course->description }}</p>
        </section>

        @if ($course->outcomes)
            <section>
                <h2 class="text-2xl font-extrabold">What you'll learn</h2>
                <ul class="mt-4 grid sm:grid-cols-2 gap-3">
                    @foreach ($course->outcomes as $o)
                        <li class="flex gap-2 text-slate-700"><svg class="w-5 h-5 text-accent-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m5 13 4 4L19 7" /></svg>{{ $o }}</li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($course->syllabus)
            <section>
                <h2 class="text-2xl font-extrabold">Course outline</h2>
                <div class="mt-4 space-y-2">
                    @foreach ($course->syllabus as $i => [$heading, $text])
                        <details class="group bg-white border border-slate-200 rounded-xl px-5 py-4" @if ($i === 0) open @endif>
                            <summary class="cursor-pointer font-semibold flex justify-between items-center">{{ $heading }}<span class="text-slate-400 group-open:rotate-180 transition">⌄</span></summary>
                            <p class="mt-2 text-slate-600 text-sm">{{ $text }}</p>
                        </details>
                    @endforeach
                </div>
            </section>
        @endif

        <section id="batches" class="scroll-mt-24">
            <h2 class="text-2xl font-extrabold">Upcoming dates & pricing</h2>
            <div class="mt-4 space-y-3">
                @forelse ($course->workshops as $w)
                    <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-wrap items-center gap-4 justify-between">
                        <div>
                            <div class="font-semibold">{{ $w->starts_at->format('D, d M Y') }} → {{ $w->ends_at->format('d M Y') }}</div>
                            <div class="text-sm text-slate-500 mt-0.5">{{ $w->batch_name }} · {{ $w->modeLabel() }} · {{ $w->location }}</div>
                            <div class="text-sm text-slate-500">Trainer: {{ $w->instructor?->name ?? 'To be announced' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-extrabold text-lg">₹{{ number_format($w->effectivePrice()) }}</div>
                            <div class="text-xs {{ $w->seatsRemaining() <= 5 ? 'text-red-600' : 'text-green-600' }} font-semibold">
                                {{ $w->isFull() ? 'Fully booked' : $w->seatsRemaining() . ' seats left' }}
                            </div>
                        </div>
                        @if ($w->isFull())
                            <span class="px-5 py-2.5 rounded-lg bg-slate-100 text-slate-500 text-sm font-semibold">Sold out</span>
                        @else
                            @auth
                                <form method="post" action="{{ route('workshops.enroll', $w) }}">@csrf
                                    <button class="px-5 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold">Enrol now</button>
                                </form>
                            @else
                                <a href="{{ route('login', ['next' => '/course/' . $course->slug]) }}" class="px-5 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold">Log in to enrol</a>
                            @endauth
                        @endif
                    </div>
                @empty
                    <p class="text-slate-600">No scheduled batches yet. Send an enquiry and we'll schedule one for you.</p>
                @endforelse
            </div>
            @guest<p class="mt-3 text-sm text-slate-500">You'll be asked to <a href="{{ route('login') }}" class="text-brand-600 underline">log in</a> or <a href="{{ route('register') }}" class="text-brand-600 underline">register</a> to complete enrolment.</p>@endguest
        </section>

        <section>
            <h2 class="text-2xl font-extrabold">Frequently asked questions</h2>
            <div class="mt-4 space-y-2">
                @foreach ([
                    ['Is there an exam included?', 'Exam vouchers vary by certification. Our advisor will confirm what is included for this course.'],
                    ['Can I reschedule my batch?', 'Yes, you can move to another batch up to 7 days before the start date, subject to availability.'],
                    ['Do you offer group discounts?', 'Yes. Teams of 3+ get special pricing. See our corporate training page.'],
                ] as [$q, $a])
                    <details class="group bg-slate-50 rounded-xl px-5 py-4"><summary class="cursor-pointer font-semibold">{{ $q }}</summary><p class="mt-2 text-sm text-slate-600">{{ $a }}</p></details>
                @endforeach
            </div>
        </section>
    </div>

    <aside class="lg:col-span-1">
        <div id="enquire" class="sticky top-24 bg-brand-50 border border-brand-100 rounded-2xl p-6 scroll-mt-24">
            <h3 class="text-lg font-extrabold">Have a question?</h3>
            <p class="text-sm text-slate-600 mb-4">Ask about dates, pricing or exam details.</p>
            <div data-vue="enquiry-form" data-props='{{ $formProps }}'></div>
        </div>
    </aside>
</div>

@if ($related->isNotEmpty())
    <section class="max-w-7xl mx-auto px-4 mt-16">
        <h2 class="text-2xl font-extrabold mb-6">Related courses</h2>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach ($related as $r) @include('site.partials.course-card', ['course' => $r]) @endforeach
        </div>
    </section>
@endif
@endsection
