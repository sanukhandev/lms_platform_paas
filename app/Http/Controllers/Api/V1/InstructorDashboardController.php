<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Http\Resources\StudentAttendanceResource;
use App\Models\Course;
use App\Models\ClassSession;
use App\Models\StudentAttendance;
use App\Models\User;
use Illuminate\Http\Request;

class InstructorDashboardController extends Controller
{
    public function myCourses(Request $request)
    {
        $courses = Course::where('instructor_id', $request->user()->id)->get();
        return CourseResource::collection($courses);
    }

    public function upcomingSessions(Request $request)
    {
        $courseIds = Course::where('instructor_id', $request->user()->id)->pluck('id');

        $sessions = ClassSession::with('course')
            ->whereIn('course_id', $courseIds)
            ->whereDate('class_date', '>=', now())
            ->orderBy('class_date')
            ->take(5)
            ->get();

        return response()->json($sessions);
    }

    public function sessionAttendance($sessionId)
    {
        $records = StudentAttendance::with('student')
            ->where('session_id', $sessionId)
            ->get();

        return StudentAttendanceResource::collection($records);
    }

    public function markAttendance(Request $request, $sessionId)
    {
        $data = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:users,id',
            'attendances.*.status' => 'required|in:present,absent,late'
        ]);

        foreach ($data['attendances'] as $item) {
            StudentAttendance::updateOrCreate(
                ['student_id' => $item['student_id'], 'session_id' => $sessionId],
                ['status' => $item['status']]
            );
        }

        return response()->json(['message' => 'Attendance updated']);
    }
}
