@extends('layouts.site')

@section('title', 'Training the World\'s Professionals')

@section('content')
{{-- Hero --}}
<section class="bg-gradient-to-br from-brand-900 via-brand-800 to-brand-600 text-white">
    <div class="max-w-7xl mx-auto px-4 py-20 lg:py-28 text-center">
        <p class="inline-block bg-white/10 text-sm px-4 py-1 rounded-full mb-5">🎓 2M+ professionals trained worldwide</p>
        <h1 class="text-4xl lg:text-6xl font-extrabold tracking-tight">Training the world's professionals</h1>
        <p class="mt-5 text-lg text-brand-100 max-w-2xl mx-auto">Industry-recognised certifications in project management, agile, ITIL, cloud, data and more. Learn online, in a classroom, or onsite.</p>
        <div class="mt-9 max-w-2xl mx-auto" data-vue="search-box" data-props='{"large":true}'></div>
        <div class="mt-6 flex flex-wrap justify-center gap-2 text-sm">
            <span class="text-brand-200">Popular:</span>
            @foreach (['PMP' => 'pmp', 'Scrum' => 'scrum', 'ITIL' => 'itil', 'AWS' => 'aws', 'Six Sigma' => 'six sigma', 'Power BI' => 'power bi'] as $label => $q)
                <a href="{{ route('courses', ['q' => $q]) }}" class="bg-white/10 hover:bg-white/20 px-3 py-1 rounded-full">{{ $label }}</a>
            @endforeach
        </div>
    </div>
</section>

{{-- Differentiators --}}
<section class="max-w-7xl mx-auto px-4 -mt-10 relative z-10">
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ([
            ['📚', 'Largest course portfolio', '500+ certification and skills courses'],
            ['📅', 'Dates to suit you', 'Weekday, weekend & fast-track batches'],
            ['🌍', 'Global venues', 'Classroom training in 490+ locations'],
            ['💻', 'Multichannel delivery', 'Classroom, online, self-paced & onsite'],
        ] as [$icon, $title, $text])
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                <div class="text-3xl">{{ $icon }}</div>
                <h3 class="font-bold mt-3">{{ $title }}</h3>
                <p class="text-sm text-slate-600 mt-1">{{ $text }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Categories --}}
<section class="max-w-7xl mx-auto px-4 mt-20">
    <div class="flex items-end justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900">Explore by category</h2>
            <p class="text-slate-600 mt-1">Find the right certification for your career path.</p>
        </div>
        <a href="{{ route('courses') }}" class="text-brand-600 font-semibold hidden sm:block">All courses →</a>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($categories as $i => $cat)
            <a href="{{ route('category', $cat) }}" class="group bg-slate-50 hover:bg-brand-50 border border-slate-200 hover:border-brand-400 rounded-2xl p-5 transition">
                <div class="w-11 h-11 rounded-xl bg-brand-600 text-white grid place-items-center font-bold">{{ Str::substr($cat->name, 0, 1) }}</div>
                <h3 class="font-bold mt-4 group-hover:text-brand-700">{{ $cat->name }}</h3>
                <p class="text-sm text-slate-500 mt-1">{{ $cat->courses_count }} courses</p>
            </a>
        @endforeach
    </div>
</section>

{{-- Featured courses --}}
<section class="max-w-7xl mx-auto px-4 mt-20">
    <h2 class="text-3xl font-extrabold text-slate-900">Top-rated courses</h2>
    <p class="text-slate-600 mt-1 mb-8">Most popular programmes chosen by learners and teams.</p>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($featured as $course)
            @include('site.partials.course-card', ['course' => $course])
        @endforeach
    </div>
</section>

{{-- Stats --}}
<section class="bg-brand-950 text-white mt-20">
    <div class="max-w-7xl mx-auto px-4 py-14 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
        @foreach ([['2M+', 'Delegates trained'], ['90K+', 'Teams upskilled'], ['15K+', 'Organisations served'], ['490+', 'Locations worldwide']] as [$n, $l])
            <div><div class="text-4xl font-extrabold text-accent-500">{{ $n }}</div><div class="text-sm text-slate-300 mt-1">{{ $l }}</div></div>
        @endforeach
    </div>
</section>

{{-- Delivery modes --}}
<section class="max-w-7xl mx-auto px-4 mt-20">
    <h2 class="text-3xl font-extrabold text-slate-900">Learn the way that suits you</h2>
    <p class="text-slate-600 mt-1 mb-8">Four flexible ways to get certified.</p>
    <div data-vue="delivery-modes"></div>
</section>

{{-- Upcoming + enquiry --}}
<section class="max-w-7xl mx-auto px-4 mt-20 grid lg:grid-cols-5 gap-10">
    <div class="lg:col-span-3">
        <div class="flex items-end justify-between mb-6">
            <h2 class="text-3xl font-extrabold text-slate-900">Upcoming batches</h2>
            <a href="{{ route('schedule') }}" class="text-brand-600 font-semibold">Full schedule →</a>
        </div>
        <div class="space-y-3">
            @forelse ($upcoming as $w)
                <a href="{{ route('course', $w->course->slug) }}" class="flex items-center gap-4 bg-white border border-slate-200 hover:border-brand-400 rounded-xl p-4 transition">
                    <div class="w-16 text-center bg-brand-50 rounded-lg py-2 shrink-0">
                        <div class="text-xs font-semibold text-brand-600 uppercase">{{ $w->starts_at->format('M') }}</div>
                        <div class="text-2xl font-extrabold text-brand-800">{{ $w->starts_at->format('d') }}</div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold truncate">{{ $w->course->title }}</div>
                        <div class="text-sm text-slate-500">{{ $w->modeLabel() }} · {{ $w->location }}</div>
                    </div>
                    <span class="text-xs font-semibold {{ $w->seatsRemaining() <= 5 ? 'text-red-600' : 'text-green-600' }}">{{ $w->seatsRemaining() }} seats left</span>
                </a>
            @empty
                <p class="text-slate-500">No upcoming batches right now.</p>
            @endforelse
        </div>
    </div>
    <div class="lg:col-span-2">
        <div class="bg-brand-50 border border-brand-100 rounded-2xl p-6">
            <h3 class="text-xl font-extrabold text-slate-900">Talk to a learning advisor</h3>
            <p class="text-sm text-slate-600 mt-1 mb-4">Tell us what you need and we'll recommend the right course.</p>
            <div data-vue="enquiry-form" data-props='{"type":"general","funding":true}'></div>
        </div>
    </div>
</section>

{{-- Enterprise --}}
<section class="max-w-7xl mx-auto px-4 mt-20">
    <div class="rounded-3xl bg-gradient-to-r from-accent-500 to-amber-400 p-10 lg:p-14 flex flex-col lg:flex-row items-center justify-between gap-6">
        <div class="text-white">
            <h2 class="text-3xl font-extrabold">Training your team? Save up to 40%</h2>
            <p class="mt-2 max-w-xl text-amber-50">Dedicated account manager, customised content, and flexible onsite or online delivery for teams of any size.</p>
        </div>
        <a href="{{ route('corporate') }}" class="bg-white text-brand-800 font-bold px-7 py-3 rounded-xl shadow hover:shadow-lg shrink-0">Explore corporate training</a>
    </div>
</section>

{{-- Accreditations --}}
<section class="max-w-7xl mx-auto px-4 mt-20 text-center">
    <p class="text-sm font-semibold uppercase tracking-wider text-slate-500">Courses aligned with leading certification bodies</p>
    <div class="mt-6 flex flex-wrap justify-center gap-x-10 gap-y-4 text-xl font-extrabold text-slate-400">
        @foreach (['PMI', 'AXELOS', 'PeopleCert', 'AWS', 'Microsoft', 'Scrum.org', 'IIBA', 'CompTIA'] as $b)<span>{{ $b }}</span>@endforeach
    </div>
</section>

{{-- Testimonials --}}
<section class="max-w-7xl mx-auto px-4 mt-20">
    <h2 class="text-3xl font-extrabold text-slate-900 mb-8">What learners say</h2>
    <div class="grid md:grid-cols-3 gap-6">
        @foreach ([
            ['The trainer made PMP concepts click. I cleared the exam on my first attempt!', 'Ankit Mehra', 'Project Manager, Infosys'],
            ['Excellent live online sessions and great support team. Highly recommended.', 'Sneha Iyer', 'Scrum Master, Accenture'],
            ['We trained 40 engineers onsite. Smooth coordination and real business impact.', 'Rohit Nair', 'L&D Head, FinServe'],
        ] as [$quote, $name, $role])
            <figure class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="text-amber-500">★★★★★</div>
                <blockquote class="mt-3 text-slate-700">“{{ $quote }}”</blockquote>
                <figcaption class="mt-4 text-sm"><span class="font-semibold">{{ $name }}</span><span class="text-slate-500"> · {{ $role }}</span></figcaption>
            </figure>
        @endforeach
    </div>
</section>

{{-- Blog --}}
<section class="max-w-7xl mx-auto px-4 mt-20">
    <div class="flex items-end justify-between mb-8">
        <h2 class="text-3xl font-extrabold text-slate-900">Knowledge centre</h2>
        <a href="{{ route('blog') }}" class="text-brand-600 font-semibold">All articles →</a>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
        @foreach ($posts as $post)
            @include('site.partials.post-card', ['post' => $post])
        @endforeach
    </div>
</section>
@endsection
