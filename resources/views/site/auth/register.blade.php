@extends('layouts.site')

@section('title', 'Create Account')

@section('content')
<div class="max-w-md mx-auto px-4 mt-14">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8">
        <h1 class="text-2xl font-extrabold">Create your account</h1>
        <p class="text-sm text-slate-600 mt-1">Enrol in courses and track your learning.</p>
        <form method="post" action="{{ route('register') }}" class="mt-6 space-y-4">
            @csrf
            @foreach ([['name', 'Full name', 'text'], ['email', 'Email', 'email'], ['password', 'Password (min 8 characters)', 'password'], ['password_confirmation', 'Confirm password', 'password']] as [$field, $label, $type])
                <div>
                    <label class="text-sm font-medium">{{ $label }}</label>
                    <input type="{{ $type }}" name="{{ $field }}" value="{{ $type === 'password' ? '' : old($field) }}" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    @error($field)<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            @endforeach
            <button class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-3 rounded-lg">Create account</button>
        </form>
        <p class="text-sm text-slate-600 mt-5 text-center">Already registered? <a href="{{ route('login') }}" class="text-brand-600 font-semibold">Log in</a></p>
    </div>
</div>
@endsection
