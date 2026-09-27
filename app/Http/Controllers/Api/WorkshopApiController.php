<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkshopApiController extends Controller
{
    /**
     * GET /api/workshops/upcoming
     * Powers the WorkshopCalendar.vue component with real-time seat availability.
     */
    public function upcoming()
    {
        $workshops = Workshop::with('course')
            ->upcoming()
            ->where('is_active', true)
            ->get()
            ->map(fn ($workshop) => [
                'id' => $workshop->id,
                'batch_name' => $workshop->batch_name,
                'course_title' => $workshop->course->title,
                'starts_at' => $workshop->starts_at->toIso8601String(),
                'ends_at' => $workshop->ends_at->toIso8601String(),
                'seats_remaining' => $workshop->seatsRemaining(),
                'is_full' => $workshop->isFull(),
            ]);

        return response()->json($workshops);
    }

    /**
     * POST /api/workshops/{workshop}/enroll
     * Called from the Vue enrollment flow. Validates seat availability
     * before creating the enrollment record.
     */
    public function enroll(Request $request, Workshop $workshop)
    {
        if ($workshop->isFull()) {
            return response()->json(['message' => 'This batch is fully booked.'], 422);
        }

        $enrollment = Enrollment::firstOrCreate(
            ['user_id' => Auth::id(), 'workshop_id' => $workshop->id],
            ['status' => Enrollment::STATUS_PENDING, 'enrolled_at' => now()]
        );

        return response()->json([
            'message' => 'Enrollment submitted successfully.',
            'enrollment' => $enrollment,
        ], 201);
    }
}
