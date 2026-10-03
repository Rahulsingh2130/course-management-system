@extends('layouts.site')

@section('title', $post->title)
@section('description', $post->excerpt)

@section('content')
<article class="max-w-3xl mx-auto px-4 mt-12">
    <a href="{{ route('blog') }}" class="text-sm text-brand-600 font-semibold">← All articles</a>
    <p class="mt-4 text-sm font-semibold text-brand-600">{{ $post->topic }}</p>
    <h1 class="text-3xl lg:text-4xl font-extrabold mt-1">{{ $post->title }}</h1>
    <p class="text-sm text-slate-500 mt-2">{{ $post->published_at?->format('d M Y') }} · {{ $post->read_minutes }} min read</p>
    <div class="mt-8 text-slate-700 leading-relaxed space-y-4 [&_h3]:text-xl [&_h3]:font-bold [&_h3]:text-slate-900 [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:space-y-1">
        {!! strip_tags($post->body, '<p><h2><h3><h4><ul><ol><li><strong><em><a><br><blockquote>') !!}
    </div>
</article>

<section class="max-w-7xl mx-auto px-4 mt-16">
    <h2 class="text-2xl font-extrabold mb-6">More articles</h2>
    <div class="grid md:grid-cols-3 gap-6">@foreach ($more as $p) @include('site.partials.post-card', ['post' => $p]) @endforeach</div>
</section>
@endsection
