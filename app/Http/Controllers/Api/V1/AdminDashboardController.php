<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\CourseRequest;
use App\Models\StudentPayment;
use App\Models\ClassSession;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function overview()
    {
        return response()->json([
            'total_courses' => Course::count(),
            'total_requests' => CourseRequest::count(),
            'approved_requests' => CourseRequest::where('status', 'approved')->count(),
            'pending_requests' => CourseRequest::where('status', 'pending')->count(),
            'total_collected' => StudentPayment::where('status', 'paid')->sum('amount_paid'),
            'upcoming_sessions' => ClassSession::whereDate('class_date', '>=', now())->count(),
        ]);
    }

    public function pendingRequests()
    {
        $requests = CourseRequest::with(['student', 'course'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return response()->json($requests);
    }

    public function paymentSummary()
    {
        $paid = StudentPayment::where('status', 'paid')->sum('amount_paid');
        $pending = StudentPayment::where('status', 'pending')->sum('amount_paid');

        return response()->json([
            'paid_total' => $paid,
            'pending_total' => $pending
        ]);
    }

    public function upcomingSessions()
    {
        $sessions = ClassSession::with('course')
            ->whereDate('class_date', '>=', now())
            ->orderBy('class_date')
            ->limit(10)
            ->get();

        return response()->json($sessions);
    }
}
