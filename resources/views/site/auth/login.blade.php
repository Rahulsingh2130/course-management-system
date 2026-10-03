@extends('layouts.site')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto px-4 mt-14">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8">
        <h1 class="text-2xl font-extrabold">Welcome back</h1>
        <p class="text-sm text-slate-600 mt-1">Log in to manage your enrolments.</p>
        <form method="post" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-sm font-medium">Password</label>
                <input type="password" name="password" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember"> Remember me</label>
            <button class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-3 rounded-lg">Log in</button>
        </form>
        <p class="text-sm text-slate-600 mt-5 text-center">New here? <a href="{{ route('register') }}" class="text-brand-600 font-semibold">Create an account</a></p>
    </div>
</div>
@endsection
