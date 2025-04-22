<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Http\Resources\StudentPaymentResource;
use App\Http\Resources\StudentAttendanceResource;
use App\Models\ClassSession;
use App\Models\CourseRequest;
use App\Models\StudentPayment;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function myCourses(Request $request)
    {
        $courses = CourseRequest::with('course')
            ->where('student_id', $request->user()->id)
            ->where('status', 'approved')
            ->get()
            ->pluck('course');

        return CourseResource::collection($courses);
    }

    public function myPayments(Request $request)
    {
        $payments = StudentPayment::with(['course', 'paymentPlan'])
            ->where('student_id', $request->user()->id)
            ->orderByDesc('paid_on')
            ->get();

        return StudentPaymentResource::collection($payments);
    }

    public function myAttendance(Request $request)
    {
        $attendance = StudentAttendance::with(['session.course'])
            ->where('student_id', $request->user()->id)
            ->orderByDesc('session_id')
            ->get();

        return StudentAttendanceResource::collection($attendance);
    }

    public function upcomingClasses(Request $request)
    {
        $courseIds = CourseRequest::where('student_id', $request->user()->id)
            ->where('status', 'approved')
            ->pluck('course_id');

        $sessions = ClassSession::with('course')
            ->whereIn('course_id', $courseIds)
            ->whereDate('class_date', '>=', now())
            ->orderBy('class_date')
            ->take(5)
            ->get();

        return response()->json($sessions);
    }
}
