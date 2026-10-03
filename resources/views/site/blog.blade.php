@extends('layouts.site')

@section('title', 'Blog & Resources')

@section('content')
<section class="bg-brand-50 border-b border-brand-100">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h1 class="text-4xl font-extrabold">Knowledge centre</h1>
        <p class="text-slate-600 mt-2">Career guides, exam tips and industry insights.</p>
    </div>
</section>
<div class="max-w-7xl mx-auto px-4 mt-10">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($posts as $post) @include('site.partials.post-card', ['post' => $post]) @endforeach
    </div>
    <div class="mt-8">{{ $posts->links() }}</div>
</div>
@endsection
