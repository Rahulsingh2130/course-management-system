<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Professional Certification Training') | {{ config('app.name') }}</title>
    <meta name="description" content="@yield('description', 'Instructor-led and self-paced professional training in project management, agile, ITIL, cloud, data and more. Classroom, online and onsite across India.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-800 font-sans antialiased">
@php
    $navCategories = \App\Models\Category::active()->orderBy('name')->get();
    $phone = '+91 80372 44591';
@endphp

{{-- Top bar --}}
<div class="bg-brand-950 text-slate-300 text-xs">
    <div class="max-w-7xl mx-auto px-4 py-2 flex flex-wrap items-center justify-between gap-2">
        <div class="flex items-center gap-4">
            <a href="tel:{{ preg_replace('/\s/', '', $phone) }}" class="hover:text-white">📞 {{ $phone }} <span class="text-accent-500">· Available 24/7</span></a>
            <a href="{{ route('contact') }}" class="hidden sm:inline hover:text-white">Log a ticket</a>
        </div>
        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="hover:text-white">Hi, {{ Str::before(auth()->user()->name, ' ') }}</a>
                @if (auth()->user()->isAdmin())<a href="{{ route('admin.dashboard') }}" class="hover:text-white">Admin</a>@endif
                <form method="post" action="{{ route('logout') }}">@csrf<button class="hover:text-white">Logout</button></form>
            @else
                <a href="{{ route('login') }}" class="hover:text-white">Login</a>
                <a href="{{ route('register') }}" class="hover:text-white">Register</a>
            @endauth
        </div>
    </div>
</div>

{{-- Header --}}
<header class="bg-white border-b border-slate-200 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-xl text-brand-800 shrink-0">
            <span class="w-8 h-8 rounded-lg bg-brand-600 text-white grid place-items-center text-base">CM</span>
            <span class="hidden sm:inline">{{ config('app.name') }}</span>
        </a>

        <nav class="hidden lg:flex items-center gap-1 text-sm font-medium">
            <div class="relative group">
                <a href="{{ route('courses') }}" class="px-3 py-2 rounded-md hover:bg-slate-100 inline-flex items-center gap-1">Courses
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6" /></svg>
                </a>
                <div class="absolute left-0 top-full pt-2 hidden group-hover:block group-focus-within:block">
                    <div class="bg-white rounded-xl shadow-xl border border-slate-100 w-72 p-2">
                        @foreach ($navCategories as $cat)
                            <a href="{{ route('category', $cat) }}" class="block px-3 py-2 rounded-lg hover:bg-brand-50 hover:text-brand-700">{{ $cat->name }}</a>
                        @endforeach
                        <a href="{{ route('courses') }}" class="block px-3 py-2 mt-1 border-t border-slate-100 text-brand-600 font-semibold">View all courses →</a>
                    </div>
                </div>
            </div>
            <a href="{{ route('schedule') }}" class="px-3 py-2 rounded-md hover:bg-slate-100">Schedule</a>
            <a href="{{ route('corporate') }}" class="px-3 py-2 rounded-md hover:bg-slate-100">Corporate Training</a>
            <a href="{{ route('offers') }}" class="px-3 py-2 rounded-md hover:bg-slate-100 text-accent-600">Offers</a>
            <a href="{{ route('blog') }}" class="px-3 py-2 rounded-md hover:bg-slate-100">Resources</a>
            <a href="{{ route('about') }}" class="px-3 py-2 rounded-md hover:bg-slate-100">About</a>
        </nav>

        <div class="flex items-center gap-3">
            <div class="hidden md:block w-64 xl:w-72" data-vue="search-box" data-props='{"placeholder":"Search courses..."}'></div>
            <a href="{{ route('contact') }}" class="hidden sm:inline-block bg-accent-500 hover:bg-accent-600 text-white text-sm font-semibold px-4 py-2 rounded-lg">Enquire</a>
            <button class="lg:hidden p-2" data-toggle="mobile-nav" aria-label="Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
        </div>
    </div>

    <div id="mobile-nav" class="hidden lg:hidden border-t border-slate-200 bg-white px-4 py-3 space-y-1 text-sm font-medium">
        <div class="md:hidden pb-2" data-vue="search-box" data-props='{"placeholder":"Search courses..."}'></div>
        <a href="{{ route('courses') }}" class="block py-2">All Courses</a>
        @foreach ($navCategories as $cat)
            <a href="{{ route('category', $cat) }}" class="block py-1.5 pl-4 text-slate-600">{{ $cat->name }}</a>
        @endforeach
        <a href="{{ route('schedule') }}" class="block py-2">Schedule</a>
        <a href="{{ route('corporate') }}" class="block py-2">Corporate Training</a>
        <a href="{{ route('offers') }}" class="block py-2">Offers</a>
        <a href="{{ route('blog') }}" class="block py-2">Resources</a>
        <a href="{{ route('about') }}" class="block py-2">About</a>
        <a href="{{ route('contact') }}" class="block py-2">Contact</a>
    </div>
</header>

@if (session('success'))
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <div class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
    </div>
@endif
@if (session('error'))
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <div class="rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div>
    </div>
@endif

<main>
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-brand-950 text-slate-300 mt-20">
    <div class="max-w-7xl mx-auto px-4 py-14 grid gap-10 md:grid-cols-2 lg:grid-cols-5">
        <div class="lg:col-span-2">
            <div class="flex items-center gap-2 font-extrabold text-xl text-white">
                <span class="w-8 h-8 rounded-lg bg-brand-600 grid place-items-center text-base">CM</span> {{ config('app.name') }}
            </div>
            <p class="mt-4 text-sm leading-relaxed text-slate-400 max-w-sm">Training the world's professionals with expert-led courses in project management, agile, IT service management, cloud, data and more.</p>
            <p class="mt-6 text-sm font-semibold text-white">Get offers & career tips</p>
            <div class="mt-2 max-w-sm" data-vue="enquiry-form" data-props='{"type":"newsletter","variant":"newsletter"}'></div>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Courses</h4>
            <ul class="space-y-2 text-sm">
                @foreach ($navCategories as $cat)
                    <li><a href="{{ route('category', $cat) }}" class="hover:text-white">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Company</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('about') }}" class="hover:text-white">About us</a></li>
                <li><a href="{{ route('corporate') }}" class="hover:text-white">Corporate training</a></li>
                <li><a href="{{ route('schedule') }}" class="hover:text-white">Course schedule</a></li>
                <li><a href="{{ route('offers') }}" class="hover:text-white">Offers</a></li>
                <li><a href="{{ route('blog') }}" class="hover:text-white">Blog & resources</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Contact</h4>
            <ul class="space-y-2 text-sm">
                <li>📞 {{ $phone }}</li>
                <li>✉ support@coursemanagement.test</li>
                <li>🕒 Support available 24/7</li>
            </ul>
            <div class="flex gap-3 mt-4 text-slate-400 text-sm">
                <a href="#" class="hover:text-white">LinkedIn</a><a href="#" class="hover:text-white">X</a><a href="#" class="hover:text-white">YouTube</a>
            </div>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 py-5 flex flex-wrap justify-between gap-2 text-xs text-slate-500">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved. GST extra as applicable.</p>
            <p class="space-x-4"><a href="#" class="hover:text-white">Terms</a><a href="#" class="hover:text-white">Privacy</a><a href="#" class="hover:text-white">Cookies</a></p>
        </div>
    </div>
</footer>
</body>
</html>
