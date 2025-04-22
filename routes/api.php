<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\CourseRequestController;
use App\Http\Controllers\Api\V1\PaymentPlanController;
use App\Http\Controllers\Api\V1\StudentPaymentController;
use App\Http\Controllers\Api\V1\ClassSessionController;
use App\Http\Controllers\Api\V1\StudentAttendanceController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::apiResource('courses', CourseController::class);
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/course-requests', [CourseRequestController::class, 'index']);
    Route::post('/course-requests', [CourseRequestController::class, 'store']);
    Route::patch('/course-requests/{courseRequest}/approve', [CourseRequestController::class, 'approve']);
    Route::patch('/course-requests/{courseRequest}/reject', [CourseRequestController::class, 'reject']);
});



Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::apiResource('payment-plans', PaymentPlanController::class)->except(['show']);
});


Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/student-payments', [StudentPaymentController::class, 'index']);
    Route::post('/student-payments', [StudentPaymentController::class, 'store']); // admin only
});


Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::post('/class-sessions/generate', [ClassSessionController::class, 'generate']);
    Route::get('/courses/{course}/class-sessions', [ClassSessionController::class, 'index']);
});


Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::post('/attendance', [StudentAttendanceController::class, 'mark']); // Admin/Instructor
    Route::get('/attendance/session/{sessionId}', [StudentAttendanceController::class, 'sessionAttendance']); // Instructor view
    Route::get('/attendance/my', [StudentAttendanceController::class, 'myAttendance']); // Student view
});
