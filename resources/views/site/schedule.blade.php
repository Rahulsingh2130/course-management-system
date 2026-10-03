@extends('layouts.site')

@section('title', 'Course Schedule')

@section('content')
<section class="bg-brand-50 border-b border-brand-100">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <h1 class="text-3xl lg:text-4xl font-extrabold">Course schedule</h1>
        <p class="text-slate-600 mt-1">Upcoming batches across classroom, online and onsite delivery.</p>
        <form method="get" class="mt-5 flex flex-wrap gap-3">
            <select name="mode" onchange="this.form.submit()" class="border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white">
                <option value="">All modes</option>
                @foreach (\App\Models\Workshop::MODES as $k => $label)<option value="{{ $k }}" @selected(request('mode') === $k)>{{ $label }}</option>@endforeach
            </select>
            <select name="city" onchange="this.form.submit()" class="border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white">
                <option value="">All cities</option>
                @foreach ($cities as $c)<option @selected(request('city') === $c)>{{ $c }}</option>@endforeach
            </select>
            @if (request()->hasAny(['mode', 'city']))<a href="{{ route('schedule') }}" class="self-center text-sm text-brand-600 font-semibold">Clear</a>@endif
        </form>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 mt-8">
    @php $grouped = $workshops->groupBy(fn ($w) => $w->starts_at->format('F Y')); @endphp
    @forelse ($grouped as $month => $items)
        <h2 class="text-xl font-extrabold mt-8 mb-3">{{ $month }}</h2>
        <div class="bg-white border border-slate-200 rounded-2xl overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-slate-500"><tr>
                    <th class="px-4 py-3">Date</th><th class="px-4 py-3">Course</th><th class="px-4 py-3">Mode</th><th class="px-4 py-3">Location</th><th class="px-4 py-3">Fee</th><th class="px-4 py-3">Seats</th><th class="px-4 py-3"></th>
                </tr></thead>
                <tbody>
                @foreach ($items as $w)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3 whitespace-nowrap font-semibold">{{ $w->starts_at->format('d M') }} – {{ $w->ends_at->format('d M') }}</td>
                        <td class="px-4 py-3"><a href="{{ route('course', $w->course->slug) }}" class="font-medium hover:text-brand-600">{{ $w->course->title }}</a><div class="text-xs text-slate-500">{{ $w->course->category->name }}</div></td>
                        <td class="px-4 py-3">{{ $w->modeLabel() }}</td>
                        <td class="px-4 py-3">{{ $w->location }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">₹{{ number_format($w->effectivePrice()) }}</td>
                        <td class="px-4 py-3"><span class="{{ $w->seatsRemaining() <= 5 ? 'text-red-600' : 'text-green-600' }} font-semibold">{{ $w->seatsRemaining() }}</span></td>
                        <td class="px-4 py-3"><a href="{{ route('course', $w->course->slug) }}#batches" class="text-brand-600 font-semibold whitespace-nowrap">Book →</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p class="text-slate-600">No batches match your filters.</p>
    @endforelse
</div>
@endsection
