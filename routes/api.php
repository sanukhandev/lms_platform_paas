<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{
    AuthController,
    CourseController,
    CourseCategoryController,
    InstructorController,
    StudentController,
    BatchController,
    ClassSessionController,
    AdminDashboardController
};

// ✅ Get Authenticated User Info
Route::middleware('auth:sanctum')->get('/user', fn(Request $request) => $request->user());

// ✅ Public Auth Routes
Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// ✅ Authenticated User Routes
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // 🔹 Resource Routes
    Route::apiResources([
        'courses' => CourseController::class,
        'course-categories' => CourseCategoryController::class,
        'instructors' => InstructorController::class,
        'students' => StudentController::class,
        'batches' => BatchController::class,
    ]);

    Route::get('instructor/overview', [InstructorController::class, 'getOverview']);
    Route::get('instructor/classes', [InstructorController::class, 'getClasses']);

    // 🔹 Batch Specific
    Route::put('batches/{batchId}/students', [BatchController::class, 'addStudents']);
    Route::get('batches/course/{courseId}', [BatchController::class, 'getByCourse']);

    // 🔹 Class Sessions (Nested under Batches)
    Route::prefix('batches/{batchId}/sessions')->group(function () {
        Route::get('/', [ClassSessionController::class, 'index']);
        Route::post('/', [ClassSessionController::class, 'store']);
        Route::get('/today', [ClassSessionController::class, 'today']);
        Route::get('/upcoming', [ClassSessionController::class, 'upcoming']);
        Route::get('/grouped', [ClassSessionController::class, 'groupedByDate']);
        Route::get('/range', [ClassSessionController::class, 'byDateRange']);
        Route::get('/by-time', [ClassSessionController::class, 'byTime']);
        Route::get('/by-date/{date}', [ClassSessionController::class, 'byDate']);
    });

    // 🔹 Individual Class Session Routes
    Route::prefix('sessions/{id}')->group(function () {
        Route::get('/', [ClassSessionController::class, 'show']);
        Route::put('/', [ClassSessionController::class, 'update']);
        Route::delete('/', [ClassSessionController::class, 'destroy']);
        Route::put('/status', [ClassSessionController::class, 'updateStatus']);
        Route::post('/generate-meeting-link', [ClassSessionController::class, 'generateMeetingLink']);
        Route::post('/reschedule', [ClassSessionController::class, 'reschedule']);
        Route::post('/cancel', [ClassSessionController::class, 'cancel']);
    });

    // 🔹 Session Validation
    Route::get('session/valid/{roomId}', [ClassSessionController::class, 'isInSession']);
});

// ✅ Admin-Specific Routes
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('v1')->group(function () {
    Route::get('admin/overview', [AdminDashboardController::class, 'overview']);
    Route::get('admin/todays-classes', [AdminDashboardController::class, 'todaysClasses']);
});
