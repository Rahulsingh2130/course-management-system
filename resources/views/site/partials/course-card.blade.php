@php $modes = $course->relationLoaded('workshops') ? $course->workshops->pluck('mode')->unique() : collect(); @endphp
<a href="{{ route('course', $course->slug) }}" class="group bg-white rounded-2xl border border-slate-200 hover:border-brand-400 hover:shadow-lg transition flex flex-col overflow-hidden">
    <div class="h-28 bg-gradient-to-br from-brand-600 to-brand-900 p-4 flex items-end justify-between relative">
        <span class="text-xs font-semibold bg-white/20 text-white px-2.5 py-1 rounded-full backdrop-blur">{{ $course->category->name }}</span>
        @if ($course->is_featured)<span class="absolute top-3 right-3 text-[11px] font-bold bg-accent-500 text-white px-2 py-0.5 rounded">POPULAR</span>@endif
    </div>
    <div class="p-5 flex-1 flex flex-col">
        <h3 class="font-bold text-slate-900 group-hover:text-brand-700 leading-snug">{{ $course->title }}</h3>
        <p class="text-sm text-slate-600 mt-2 line-clamp-2">{{ $course->short_description }}</p>
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 mt-4">
            <span>⏱ {{ $course->duration_days }} {{ Str::plural('day', $course->duration_days) }}</span>
            <span>📶 {{ $course->level }}</span>
            <span class="text-amber-500 font-semibold">★ {{ $course->rating }}</span>
        </div>
        <div class="mt-auto pt-5 flex items-end justify-between">
            <div>
                <span class="text-xs text-slate-500">From</span>
                <div class="text-lg font-extrabold text-slate-900">₹{{ number_format($course->price) }}<span class="text-xs font-medium text-slate-500"> + GST</span></div>
            </div>
            <span class="text-sm font-semibold text-brand-600">View details →</span>
        </div>
    </div>
</a>
