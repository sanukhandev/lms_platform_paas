<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    CourseController,
    CourseRequestController,
    PaymentPlanController,
    StudentPaymentController,
    ClassSessionController,
    StudentAttendanceController,
    StudentDashboardController,
    InstructorDashboardController,
    AdminDashboardController,
    CourseMaterialController,
    AuthController,
    InstructorController,
    StudentController
};

// ✅ Authenticated User Info
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ✅ API V1 - Global Auth Group
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // 🧑‍🏫 Course Management
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('instructors', InstructorController::class);
    Route::apiResource('students', StudentController::class);


    // 🧑‍🎓 Course Enrollment Requests
    Route::get('/course-requests', [CourseRequestController::class, 'index']);
    Route::post('/course-requests', [CourseRequestController::class, 'store']);
    Route::patch('/course-requests/{courseRequest}/approve', [CourseRequestController::class, 'approve']);
    Route::patch('/course-requests/{courseRequest}/reject', [CourseRequestController::class, 'reject']);

    // 💸 Payment Plans
    Route::apiResource('payment-plans', PaymentPlanController::class)->except(['show']);

    // 💰 Student Payments
    Route::get('/student-payments', [StudentPaymentController::class, 'index']);
    Route::post('/student-payments', [StudentPaymentController::class, 'store']); // Admin only

    // 🗓️ Class Sessions
    Route::post('/class-sessions/generate', [ClassSessionController::class, 'generate']);
    Route::get('/courses/{course}/class-sessions', [ClassSessionController::class, 'index']);
    Route::get('/courses/class-sessions/{sessionId}', [ClassSessionController::class, 'getSessionById']);
    Route::post('/class-sessions/{sessionId}/start-meeting', [ClassSessionController::class, 'startMeeting']);

    // 📝 Attendance
    Route::post('/attendance', [StudentAttendanceController::class, 'mark']); // Admin/Instructor
    Route::get('/attendance/session/{sessionId}', [StudentAttendanceController::class, 'sessionAttendance']); // Instructor
    Route::get('/attendance/my', [StudentAttendanceController::class, 'myAttendance']); // Student
});

// 🎓 Student Dashboard
Route::middleware(['auth:sanctum', 'role:student'])->prefix('v1/student')->group(function () {
    Route::get('/my-courses', [StudentDashboardController::class, 'myCourses']);
    Route::get('/my-payments', [StudentDashboardController::class, 'myPayments']);
    Route::get('/my-attendance', [StudentDashboardController::class, 'myAttendance']);
    Route::get('/upcoming-classes', [StudentDashboardController::class, 'upcomingClasses']);
});

// 🧑‍🏫 Instructor Dashboard
Route::middleware(['auth:sanctum', 'role:instructor'])->prefix('v1/instructor')->group(function () {
    Route::get('/my-courses', [InstructorDashboardController::class, 'myCourses']);
    Route::get('/upcoming-sessions', [InstructorDashboardController::class, 'upcomingSessions']);
    Route::get('/session/{sessionId}/attendance', [InstructorDashboardController::class, 'sessionAttendance']);
    Route::post('/session/{sessionId}/attendance', [InstructorDashboardController::class, 'markAttendance']);
});

// 👩‍💼 Admin Dashboard
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('v1/admin')->group(function () {
    Route::get('/overview', [AdminDashboardController::class, 'overview']);
    Route::get('/pending-requests', [AdminDashboardController::class, 'pendingRequests']);
    Route::get('/payment-summary', [AdminDashboardController::class, 'paymentSummary']);
    Route::get('/upcoming-sessions', [AdminDashboardController::class, 'upcomingSessions']);
});


Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::post('/materials/upload', [CourseMaterialController::class, 'upload']);
    Route::get('/materials/{courseId}', [CourseMaterialController::class, 'list']);
    Route::get('/materials/download/{id}', [CourseMaterialController::class, 'download'])->name('materials.download');
});



Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
