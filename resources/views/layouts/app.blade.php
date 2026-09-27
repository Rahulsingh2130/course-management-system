<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Course Management System') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-800">
    <nav class="bg-white shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <span class="font-semibold text-lg">Course Management System</span>
            <div class="space-x-4 text-sm">
                <a href="/admin" class="hover:text-indigo-600">Dashboard</a>
                <a href="/admin/courses" class="hover:text-indigo-600">Courses</a>
                <a href="/admin/workshops" class="hover:text-indigo-600">Workshops</a>
                <a href="/admin/enrollments" class="hover:text-indigo-600">Enrollments</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-6">
        @if (session('message'))
            <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">
                {{ session('message') }}
            </div>
        @endif

        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
