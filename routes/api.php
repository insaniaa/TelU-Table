<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/courses', [CourseController::class, 'index']); // Get all courses with pagination
Route::get('/courses/{id}', [CourseController::class, 'show']); // Get course by ID
Route::get('/courses/search', [CourseController::class, 'search']); // Search courses

