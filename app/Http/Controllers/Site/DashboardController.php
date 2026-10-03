<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Workshop;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $enrollments = $request->user()->enrollments()
            ->with('workshop.course')
            ->latest('enrolled_at')
            ->get();

        return view('site.dashboard', [
            'upcoming' => $enrollments->filter(fn ($e) => $e->status !== 'cancelled' && $e->workshop->starts_at->isFuture()),
            'past' => $enrollments->filter(fn ($e) => $e->status === 'cancelled' || $e->workshop->starts_at->isPast()),
        ]);
    }

    public function enroll(Request $request, Workshop $workshop)
    {
        if (! $workshop->is_active || $workshop->starts_at->isPast()) {
            return back()->with('error', 'This batch is no longer open for enrolment.');
        }

        $existing = Enrollment::where('user_id', $request->user()->id)->where('workshop_id', $workshop->id)->first();

        if ($existing && $existing->status !== Enrollment::STATUS_CANCELLED) {
            return back()->with('error', 'You are already enrolled in this batch.');
        }

        if ($workshop->isFull()) {
            return back()->with('error', 'This batch is fully booked. Please choose another date.');
        }

        Enrollment::updateOrCreate(
            ['user_id' => $request->user()->id, 'workshop_id' => $workshop->id],
            ['status' => Enrollment::STATUS_PENDING, 'enrolled_at' => now()]
        );

        return redirect()->route('dashboard')->with('success', 'Enrolment submitted! We will confirm your seat shortly.');
    }

    public function cancel(Request $request, Enrollment $enrollment)
    {
        abort_unless($enrollment->user_id === $request->user()->id, 403);

        $enrollment->update(['status' => Enrollment::STATUS_CANCELLED]);

        return back()->with('success', 'Enrolment cancelled.');
    }
}
