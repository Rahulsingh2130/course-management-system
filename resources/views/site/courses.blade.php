@extends('layouts.site')

@section('title', $currentCategory ? $currentCategory->name . ' Courses' : 'All Courses')

@section('content')
<section class="bg-brand-50 border-b border-brand-100">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <nav class="text-sm text-slate-500"><a href="{{ route('home') }}" class="hover:text-brand-600">Home</a> / <a href="{{ route('courses') }}" class="hover:text-brand-600">Courses</a>@if ($currentCategory) / {{ $currentCategory->name }}@endif</nav>
        <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 mt-2">{{ $currentCategory?->name ?? 'All Courses' }}</h1>
        <p class="text-slate-600 mt-1">{{ $courses->total() }} {{ Str::plural('course', $courses->total()) }} found</p>
        <div class="mt-5 max-w-2xl" data-vue="search-box" data-props='{{ json_encode(["initial" => $filters["q"] ?? ""]) }}'></div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 mt-8 grid lg:grid-cols-4 gap-8">
    <aside class="lg:col-span-1">
        <form method="get" action="{{ $currentCategory ? route('category', $currentCategory) : route('courses') }}" class="space-y-6">
            @if (! empty($filters['q']))<input type="hidden" name="q" value="{{ $filters['q'] }}">@endif

            <div>
                <h3 class="font-bold text-sm uppercase tracking-wide text-slate-500 mb-2">Categories</h3>
                <ul class="space-y-1 text-sm">
                    <li><a href="{{ route('courses') }}" class="block px-3 py-1.5 rounded-lg {{ ! $currentCategory ? 'bg-brand-600 text-white' : 'hover:bg-slate-100' }}">All categories</a></li>
                    @foreach ($categories as $cat)
                        <li><a href="{{ route('category', $cat) }}" class="flex justify-between px-3 py-1.5 rounded-lg {{ $currentCategory?->id === $cat->id ? 'bg-brand-600 text-white' : 'hover:bg-slate-100' }}">
                            <span>{{ $cat->name }}</span><span class="opacity-70">{{ $cat->courses_count }}</span></a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="font-bold text-sm uppercase tracking-wide text-slate-500 mb-2">Level</h3>
                <select name="level" onchange="this.form.submit()" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Any level</option>
                    @foreach (['Foundation', 'Intermediate', 'Advanced'] as $lv)
                        <option @selected(($filters['level'] ?? '') === $lv)>{{ $lv }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <h3 class="font-bold text-sm uppercase tracking-wide text-slate-500 mb-2">Delivery mode</h3>
                <select name="mode" onchange="this.form.submit()" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Any mode</option>
                    @foreach (\App\Models\Workshop::MODES as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['mode'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <h3 class="font-bold text-sm uppercase tracking-wide text-slate-500 mb-2">Sort by</h3>
                <select name="sort" onchange="this.form.submit()" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    @foreach (['popular' => 'Most popular', 'newest' => 'Newest', 'price_asc' => 'Price: low to high', 'price_desc' => 'Price: high to low'] as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['sort'] ?? 'popular') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <a href="{{ $currentCategory ? route('category', $currentCategory) : route('courses') }}" class="text-sm text-brand-600 font-semibold">Clear filters</a>
        </form>
    </aside>

    <section class="lg:col-span-3">
        @if ($courses->isEmpty())
            <div class="text-center bg-slate-50 border border-dashed border-slate-300 rounded-2xl p-12">
                <p class="text-lg font-semibold">No courses match your filters.</p>
                <p class="text-slate-600 mt-1 mb-6">Can't find what you're looking for? Tell us and we'll arrange it.</p>
                <div class="max-w-sm mx-auto text-left" data-vue="enquiry-form" data-props='{"type":"general","message":true,"submitLabel":"Request this course"}'></div>
            </div>
        @else
            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach ($courses as $course)
                    @include('site.partials.course-card', ['course' => $course])
                @endforeach
            </div>
            <div class="mt-8">{{ $courses->links() }}</div>
        @endif
    </section>
</div>
@endsection
