@extends('layouts.site')

@section('title', 'Contact Us')

@section('content')
<section class="bg-brand-50 border-b border-brand-100">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h1 class="text-4xl font-extrabold">Contact us</h1>
        <p class="text-slate-600 mt-2">Our learning advisors are available 24/7. Call, email or send us a message.</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 mt-10 grid lg:grid-cols-5 gap-10">
    <div class="lg:col-span-2 space-y-4">
        @foreach ([['📞', 'Phone', '+91 80372 44591 (24/7)'], ['✉', 'Email', 'support@coursemanagement.test'], ['📍', 'Head office', 'Bengaluru, Karnataka, India'], ['🎫', 'Log a ticket', 'Existing learner? Send us your booking reference.']] as [$i, $t, $d])
            <div class="flex gap-4 border border-slate-200 rounded-xl p-5"><div class="text-2xl">{{ $i }}</div><div><div class="font-bold">{{ $t }}</div><div class="text-sm text-slate-600">{{ $d }}</div></div></div>
        @endforeach
    </div>
    <div class="lg:col-span-3 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <h2 class="text-xl font-extrabold mb-4">Send us a message</h2>
        @php $u = auth()->user(); @endphp
        <div data-vue="enquiry-form" data-props='{{ json_encode(["type" => "contact", "message" => true, "funding" => true, "submitLabel" => "Send message", "user" => $u ? ["name" => $u->name, "email" => $u->email] : new stdClass]) }}'></div>
    </div>
</div>
@endsection
