<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\LogbookController;
use App\Http\Controllers\Api\MicroSkillController;
use App\Http\Controllers\Api\SharingSessionController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Mobile API Routes
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/fcm-token', [AuthController::class, 'updateFcmToken']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::post('/profile/update-photo', [AuthController::class, 'updatePhoto']);

    // Attendance
    Route::get('/attendance/today', [AttendanceController::class, 'today']);
    Route::get('/attendance/history', [AttendanceController::class, 'history']);
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut']);
    Route::get('/attendance/photo/{filename}', [AttendanceController::class, 'servePhoto']);

    // Logbooks
    Route::get('/logbooks/photo/{filename}', [LogbookController::class, 'servePhoto'])->where('filename', '[^/]+');
    Route::get('/logbooks', [LogbookController::class, 'index']);
    Route::post('/logbooks', [LogbookController::class, 'store']);
    Route::put('/logbooks/{id}', [LogbookController::class, 'update']);
    Route::delete('/logbooks/{id}', [LogbookController::class, 'destroy']);

    // Micro Skills
    Route::get('/micro-skills', [MicroSkillController::class, 'index']);
    Route::post('/micro-skills', [MicroSkillController::class, 'store']);
    Route::post('/micro-skills/{id}', [MicroSkillController::class, 'update']); // Use POST because of multipart/form-data
    Route::delete('/micro-skills/{id}', [MicroSkillController::class, 'destroy']);
    Route::get('/micro-skills/photo/{filename}', [MicroSkillController::class, 'servePhoto']);

    // Sharing Sessions
    Route::get('/sharing-sessions', [SharingSessionController::class, 'index']);
    Route::get('/sharing-sessions/{id}', [SharingSessionController::class, 'show']);
    Route::post('/sharing-sessions/{id}/update-materi', [SharingSessionController::class, 'updateMateri']);
});
