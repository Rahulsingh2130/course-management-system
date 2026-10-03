@extends('layouts.site')

@section('title', 'About Us')

@section('content')
<section class="bg-brand-50 border-b border-brand-100">
    <div class="max-w-4xl mx-auto px-4 py-16 text-center">
        <h1 class="text-4xl font-extrabold">About {{ config('app.name') }}</h1>
        <p class="mt-4 text-lg text-slate-600">We help professionals and organisations grow through world-class, certification-focused training delivered the way you want to learn.</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 mt-14 grid sm:grid-cols-2 lg:grid-cols-4 gap-6 text-center">
    @foreach ([['2M+', 'Delegates trained'], ['90K+', 'Teams upskilled'], ['15K+', 'Organisations'], ['490+', 'Locations']] as [$n, $l])
        <div class="border border-slate-200 rounded-2xl py-8"><div class="text-4xl font-extrabold text-brand-600">{{ $n }}</div><div class="text-slate-600 mt-1">{{ $l }}</div></div>
    @endforeach
</section>

<section class="max-w-4xl mx-auto px-4 mt-14 space-y-10">
    <div>
        <h2 class="text-2xl font-extrabold">Our mission</h2>
        <p class="mt-3 text-slate-700 leading-relaxed">To make high-quality professional education accessible, flexible and measurable. From a single learner preparing for their first certification to enterprises upskilling thousands, we provide the courses, trainers and support to turn learning into career results.</p>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
        @foreach ([['Expert trainers', 'Certified practitioners with real industry experience.'], ['Flexible learning', 'Classroom, live online, self-paced and onsite options.'], ['Learner first', '24/7 support from enquiry to exam day.']] as [$t, $d])
            <div class="bg-slate-50 rounded-2xl p-6"><h3 class="font-bold">{{ $t }}</h3><p class="text-sm text-slate-600 mt-1">{{ $d }}</p></div>
        @endforeach
    </div>
    <div class="text-center"><a href="{{ route('courses') }}" class="inline-block bg-brand-600 hover:bg-brand-700 text-white font-semibold px-7 py-3 rounded-lg">Browse courses</a></div>
</section>
@endsection
