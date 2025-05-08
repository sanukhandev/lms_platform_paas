<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{
    Course,
    ClassSession,
    User
};

class AdminDashboardController extends Controller
{
    public function overview()
    {
        $totalCourses = Course::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        return response()->json([
            'total_courses' => $totalCourses,
            'total_students' => $totalStudents,
            'total_instructors' => $totalInstructors,
        ]);
    }

    public function todaysClasses()
    {
        $today = now()->format('Y-m-d');
        $todaysClasses = ClassSession::whereDate('date', $today)->with(['batch.course.instructor'])->get();
        return response()->json($todaysClasses);
    }
}
