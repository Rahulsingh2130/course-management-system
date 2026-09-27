<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseApiController extends Controller
{
    /**
     * GET /api/courses
     * Returns only active courses under active categories, with optional
     * category filter and search — mirrors the platform's public catalog.
     */
    public function index(Request $request)
    {
        $courses = Course::visibleToPublic()
            ->with('category')
            ->when($request->query('category'), fn ($q, $slug) => $q->whereHas('category', fn ($c) => $c->where('slug', $slug)))
            ->when($request->query('search'), fn ($q, $term) => $q->where('title', 'like', "%{$term}%"))
            ->latest()
            ->paginate(12);

        return response()->json($courses);
    }

    public function show(string $slug)
    {
        $course = Course::visibleToPublic()
            ->with(['category', 'workshops' => fn ($q) => $q->upcoming()])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($course);
    }

    public function categories()
    {
        return response()->json(Category::active()->withCount('courses')->get());
    }
}
