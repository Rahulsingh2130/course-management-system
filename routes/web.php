<?php

use App\Http\Livewire\AdminDashboard;
use App\Http\Livewire\CourseManager;
use App\Http\Livewire\EnrollmentManager;
use App\Http\Livewire\WorkshopScheduler;
use Illuminate\Support\Facades\Route;

// Public catalog — renders the Vue.js CourseCatalog + WorkshopCalendar components
Route::view('/', 'courses.public-listing')->name('home');

// Admin panel — protected by auth + admin-role middleware in production
Route::middleware(['auth', 'can:admin'])->prefix('admin')->group(function () {
    Route::get('/', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/courses', CourseManager::class)->name('admin.courses');
    Route::get('/workshops', WorkshopScheduler::class)->name('admin.workshops');
    Route::get('/enrollments', EnrollmentManager::class)->name('admin.enrollments');
});
