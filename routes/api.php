<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    CourseController,
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
});


Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
