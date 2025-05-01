<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    CourseController,
    AuthController,
    InstructorController,
    StudentController,
    BatchController,
    ClassSessionController
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

    // 🔹 Batch Routes
    Route::apiResource('batches', BatchController::class);

    // add students to batch
    Route::put('batches/{batchId}/students', [BatchController::class, 'addStudents']);


    // 🔹 Class Session Routes (Nested under Batches)
    Route::get('batches/{batchId}/sessions', [ClassSessionController::class, 'index']);
    Route::post('batches/{batchId}/sessions', [ClassSessionController::class, 'store']);
    Route::get('sessions/{id}', [ClassSessionController::class, 'show']);
    Route::put('sessions/{id}', [ClassSessionController::class, 'update']);
    Route::delete('sessions/{id}', [ClassSessionController::class, 'destroy']);


    // genertae routes form generate meeting link, pospone class cance calls with class session controller with session id
    Route::post('sessions/{id}/generate-meeting-link', [ClassSessionController::class, 'generateMeetingLink']);
    Route::post('sessions/{id}/reschedule', [ClassSessionController::class, 'reschedule']);
    Route::post('sessions/{id}/cancel', [ClassSessionController::class, 'cancel']);
    // 🔹 Extra Class Session Routes
    Route::get('batches/{batchId}/sessions/today', [ClassSessionController::class, 'today']);
    Route::get('batches/{batchId}/sessions/upcoming', [ClassSessionController::class, 'upcoming']);
    Route::get('batches/{batchId}/sessions/grouped', [ClassSessionController::class, 'groupedByDate']);
    Route::get('batches/{batchId}/sessions/range', [ClassSessionController::class, 'byDateRange']);
    Route::get('batches/{batchId}/sessions/by-time', [ClassSessionController::class, 'byTime']);
    Route::get('batches/{batchId}/sessions/by-date/{date}', [ClassSessionController::class, 'byDate']);
    Route::get('batches/course/{courseId}', [BatchController::class, 'getByCourse']);
});


Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
