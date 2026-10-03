@extends('layouts.site')

@section('title', 'Offers & Discounts')

@section('content')
<section class="bg-gradient-to-r from-accent-500 to-amber-400 text-white">
    <div class="max-w-7xl mx-auto px-4 py-16 text-center">
        <p class="font-semibold uppercase tracking-widest text-sm">Limited-time offer</p>
        <h1 class="text-4xl lg:text-6xl font-extrabold mt-2">Up to 40% off selected courses</h1>
        <p class="mt-4 max-w-xl mx-auto text-amber-50">Enter your email and our advisor will send you the best current deal for the course you want.</p>
        <div class="mt-8 max-w-md mx-auto bg-white rounded-2xl p-4 text-left" data-vue="enquiry-form" data-props='{"type":"general","submitLabel":"Claim my offer"}'></div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 mt-14">
    <h2 class="text-2xl font-extrabold mb-6">Courses featured in this offer</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($courses as $course) @include('site.partials.course-card', ['course' => $course]) @endforeach
    </div>
</section>
@endsection
