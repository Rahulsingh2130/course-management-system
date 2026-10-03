<a href="{{ route('post', $post->slug) }}" class="group bg-white border border-slate-200 hover:border-brand-400 hover:shadow-lg transition rounded-2xl overflow-hidden flex flex-col">
    <div class="h-32 bg-gradient-to-br from-slate-700 to-brand-800 p-4 flex items-end">
        <span class="text-xs font-semibold bg-white/20 text-white px-2.5 py-1 rounded-full">{{ $post->topic }}</span>
    </div>
    <div class="p-5 flex-1 flex flex-col">
        <h3 class="font-bold group-hover:text-brand-700 leading-snug">{{ $post->title }}</h3>
        <p class="text-sm text-slate-600 mt-2 line-clamp-3">{{ $post->excerpt }}</p>
        <p class="mt-auto pt-4 text-xs text-slate-500">{{ $post->published_at?->format('d M Y') }} · {{ $post->read_minutes }} min read</p>
    </div>
</a>
