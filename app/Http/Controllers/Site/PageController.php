<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Post;
use App\Models\Workshop;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('site.home', [
            'categories' => Category::active()->withCount(['courses' => fn ($q) => $q->where('is_active', true)])->orderBy('name')->get(),
            'featured' => Course::visibleToPublic()->with('category')->orderByDesc('is_featured')->orderByDesc('rating')->take(6)->get(),
            'upcoming' => Workshop::with('course')->upcoming()->where('is_active', true)->take(5)->get(),
            'posts' => Post::published()->take(3)->get(),
        ]);
    }

    public function courses(Request $request, ?Category $category = null)
    {
        $sort = $request->query('sort', 'popular');

        $query = Course::visibleToPublic()->with('category')
            ->when($category, fn ($q) => $q->where('category_id', $category->id))
            ->when($request->query('category'), fn ($q, $slug) => $q->whereHas('category', fn ($c) => $c->where('slug', $slug)))
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($w) => $w
                ->where('title', 'like', "%{$term}%")
                ->orWhere('short_description', 'like', "%{$term}%")))
            ->when($request->query('level'), fn ($q, $level) => $q->where('level', $level))
            ->when($request->query('mode'), fn ($q, $mode) => $q->whereHas('workshops', fn ($w) => $w->where('mode', $mode)->upcoming()));

        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'newest' => $query->latest(),
            default => $query->orderByDesc('is_featured')->orderByDesc('rating'),
        };

        return view('site.courses', [
            'courses' => $query->paginate(9)->withQueryString(),
            'categories' => Category::active()->withCount(['courses' => fn ($q) => $q->where('is_active', true)])->orderBy('name')->get(),
            'currentCategory' => $category ?? Category::where('slug', $request->query('category'))->first(),
            'filters' => $request->only(['q', 'level', 'mode', 'sort']),
        ]);
    }

    public function course(string $slug)
    {
        $course = Course::visibleToPublic()
            ->with(['category', 'workshops' => fn ($q) => $q->upcoming()->where('is_active', true)->with('instructor')])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('site.course', [
            'course' => $course,
            'related' => Course::visibleToPublic()->with('category')
                ->where('category_id', $course->category_id)->where('id', '!=', $course->id)->take(3)->get(),
        ]);
    }

    public function schedule(Request $request)
    {
        $workshops = Workshop::with(['course.category', 'instructor'])->upcoming()->where('is_active', true)
            ->when($request->query('mode'), fn ($q, $mode) => $q->where('mode', $mode))
            ->when($request->query('city'), fn ($q, $city) => $q->where('location', $city))
            ->get();

        return view('site.schedule', [
            'workshops' => $workshops,
            'cities' => Workshop::where('mode', 'classroom')->distinct()->orderBy('location')->pluck('location'),
        ]);
    }

    public function blog()
    {
        return view('site.blog', ['posts' => Post::published()->paginate(9)]);
    }

    public function post(Post $post)
    {
        abort_unless($post->is_published, 404);

        return view('site.post', [
            'post' => $post,
            'more' => Post::published()->where('id', '!=', $post->id)->take(3)->get(),
        ]);
    }

    public function corporate()
    {
        return view('site.corporate');
    }

    public function offers()
    {
        return view('site.offers', [
            'courses' => Course::visibleToPublic()->with('category')->orderByDesc('rating')->take(6)->get(),
        ]);
    }

    public function about()
    {
        return view('site.about');
    }

    public function contact()
    {
        return view('site.contact');
    }
}
