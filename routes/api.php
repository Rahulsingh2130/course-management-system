<?php

use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\WorkshopApiController;
use Illuminate\Support\Facades\Route;

Route::get('/courses', [CourseApiController::class, 'index']);
Route::get('/courses/{slug}', [CourseApiController::class, 'show']);
Route::get('/categories', [CourseApiController::class, 'categories']);

Route::get('/workshops/upcoming', [WorkshopApiController::class, 'upcoming']);
