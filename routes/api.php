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

    // Attendance
    Route::get('/attendance/today', [AttendanceController::class, 'today']);
    Route::get('/attendance/history', [AttendanceController::class, 'history']);
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut']);
    Route::get('/attendance/photo/{filename}', [AttendanceController::class, 'servePhoto']);

    // Logbooks
    Route::get('/logbooks', [LogbookController::class, 'index']);
    Route::post('/logbooks', [LogbookController::class, 'store']);

    // Micro Skills
    Route::get('/micro-skills', [MicroSkillController::class, 'index']);

    // Sharing Sessions
    Route::get('/sharing-sessions', [SharingSessionController::class, 'index']);
});
