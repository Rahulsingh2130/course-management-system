@extends('layouts.site')

@section('title', 'Corporate Training')

@section('content')
<section class="bg-gradient-to-br from-brand-900 to-brand-600 text-white">
    <div class="max-w-7xl mx-auto px-4 py-16 grid lg:grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-4xl lg:text-5xl font-extrabold">Upskill your entire team</h1>
            <p class="mt-4 text-lg text-brand-100">Customised onsite, online and hybrid programmes with a dedicated account manager. Save up to 40% on team bookings.</p>
            <div class="mt-6 grid grid-cols-3 gap-4 text-center">
                @foreach ([['15K+', 'Organisations'], ['90K+', 'Teams'], ['24/7', 'Support']] as [$n, $l])
                    <div class="bg-white/10 rounded-xl py-4"><div class="text-2xl font-extrabold text-accent-500">{{ $n }}</div><div class="text-xs">{{ $l }}</div></div>
                @endforeach
            </div>
        </div>
        <div class="bg-white text-slate-800 rounded-2xl p-6 shadow-2xl">
            <h2 class="text-xl font-extrabold">Request a corporate quote</h2>
            <div class="mt-4" data-vue="enquiry-form" data-props='{"type":"corporate","company":true,"message":true,"submitLabel":"Get a quote"}'></div>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 mt-16">
    <h2 class="text-3xl font-extrabold text-center">Why leading companies train with us</h2>
    <div class="grid md:grid-cols-3 gap-6 mt-10">
        @foreach ([
            ['💰', 'Budget efficiency', 'Volume discounts and flexible payment terms keep your L&D budget on track.'],
            ['⏳', 'Time savings', 'We handle scheduling, logistics and certification so your HR team does not have to.'],
            ['🤝', 'Dedicated account manager', 'A single point of contact for planning, reporting and renewals.'],
            ['🎯', 'Customised content', 'Tailor course content, case studies and examples to your industry.'],
            ['📊', 'Progress reporting', 'Track attendance, assessment scores and certification outcomes.'],
            ['🌐', 'Deliver anywhere', 'Onsite at your office, online live, or hybrid across locations.'],
        ] as [$i, $t, $d])
            <div class="border border-slate-200 rounded-2xl p-6"><div class="text-3xl">{{ $i }}</div><h3 class="font-bold mt-3">{{ $t }}</h3><p class="text-sm text-slate-600 mt-1">{{ $d }}</p></div>
        @endforeach
    </div>
</section>
@endsection
