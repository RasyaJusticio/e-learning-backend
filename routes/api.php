<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\Teacher\ClassroomInviteController;
use App\Http\Controllers\Teacher\TeacherClassroomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication Routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logout/all', [AuthController::class, 'logoutFromAll']);
    });
});

// Me Routes
Route::group(['prefix' => 'me', 'middleware' => ['auth:sanctum']], function () {
    Route::patch('', [MeController::class, 'update']);
    Route::patch('avatar', [MeController::class, 'avatar']);
});

// Classroom Routes
Route::group(['prefix' => 'classes', 'middleware' => ['auth:sanctum']], function () {
    Route::get('', [ClassroomController::class, 'index']);

    Route::group(['middleware' => ['teacher-only']], function () {
        Route::post('', [TeacherClassroomController::class, 'store']);

        Route::group(['prefix' => '{classroom:uuid}'], function () {
            Route::post('invite', [ClassroomInviteController::class, 'invite']);
        });
    });
});

// Invites Routes
Route::group(['prefix' => 'invites', 'middleware' => ['auth:sanctum', 'student-only']], function () {
    Route::get('', [InviteController::class, 'index']);
    Route::post('{invite}/respond', [InviteController::class, 'respond']);
});
