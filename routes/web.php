<?php

use App\Http\Controllers\Site\AuthController;
use App\Http\Controllers\Site\DashboardController;
use App\Http\Controllers\Site\EnquiryController;
use App\Http\Controllers\Site\PageController;
use App\Http\Livewire\AdminDashboard;
use App\Http\Livewire\BlogManager;
use App\Http\Livewire\CourseManager;
use App\Http\Livewire\EnquiryManager;
use App\Http\Livewire\EnrollmentManager;
use App\Http\Livewire\WorkshopScheduler;
use Illuminate\Support\Facades\Route;

// Public website
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/courses', [PageController::class, 'courses'])->name('courses');
Route::get('/category/{category:slug}', [PageController::class, 'courses'])->name('category');
Route::get('/course/{slug}', [PageController::class, 'course'])->name('course');
Route::get('/schedule', [PageController::class, 'schedule'])->name('schedule');
Route::get('/corporate-training', [PageController::class, 'corporate'])->name('corporate');
Route::get('/offers', [PageController::class, 'offers'])->name('offers');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{post:slug}', [PageController::class, 'post'])->name('post');

Route::post('/enquiries', [EnquiryController::class, 'store'])->middleware('throttle:10,1')->name('enquiries.store');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Student area
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/workshops/{workshop}/enroll', [DashboardController::class, 'enroll'])->name('workshops.enroll');
    Route::delete('/enrollments/{enrollment}', [DashboardController::class, 'cancel'])->name('enrollments.cancel');
});

// Admin panel
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/courses', CourseManager::class)->name('admin.courses');
    Route::get('/workshops', WorkshopScheduler::class)->name('admin.workshops');
    Route::get('/enrollments', EnrollmentManager::class)->name('admin.enrollments');
    Route::get('/enquiries', EnquiryManager::class)->name('admin.enquiries');
    Route::get('/blog', BlogManager::class)->name('admin.blog');
});
