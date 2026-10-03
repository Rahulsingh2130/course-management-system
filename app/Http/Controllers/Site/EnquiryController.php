<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:general,course,corporate,contact,newsletter',
            'course_id' => 'nullable|exists:courses,id',
            'name' => 'nullable|string|max:120',
            'email' => 'required|email|max:190',
            'phone' => 'nullable|string|max:25',
            'company' => 'nullable|string|max:150',
            'funding_source' => 'nullable|string|max:60',
            'team_size' => 'nullable|integer|min:1|max:100000',
            'message' => 'nullable|string|max:2000',
        ]);

        Enquiry::create($data);

        return response()->json([
            'message' => "Thank you! Our learning advisor will contact you shortly.",
        ], 201);
    }
}
