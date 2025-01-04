<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Models\Lecturer;
use App\Models\StudentClass;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:Super Admin'])->prefix('/admin')->name('admin.')->group(function () {
        Route::prefix('/master-data')->name('master_data.')->group(function () {
            Route::resource('/user', UserController::class)->except('destroy');;
            Route::post('/user/{user}/status', [UserController::class, 'updateStatus'])->name('user.update_status');
            Route::post('/user/{user}/delete', [UserController::class, 'destroy'])->name('user.delete');

            Route::prefix('/tabler')->name('tabler.')->group(function () {

                Route::resource('/lecturer', LecturerController::class)->except('destroy');;
                Route::post('/lecturer/{lecturer}/status', [LecturerController::class, 'updateStatus'])->name('lecturer.update_status');
                Route::post('/lecturer/{lecturer}/delete', [LecturerController::class, 'destroy'])->name('lecturer.delete');

                Route::resource('/student', StudentController::class)->except('destroy');;
                Route::post('/student/{student}/status', [StudentController::class, 'updateStatus'])->name('student.update_status');
                Route::post('/student/{student}/delete', [StudentController::class, 'destroy'])->name('student.delete');
            });

            Route::resource('/student-class', StudentClassController::class)->names('student_class')->except('destroy');
            Route::post('/student-class/{student_class}/status', [StudentClassController::class, 'updateStatus'])->name('student_class.update_status');
            Route::post('/student-class/{student_class}/delete', [StudentClassController::class, 'destroy'])->name('student_class.delete');

            Route::resource('/room', RoomController::class)->except('destroy');;
            Route::post('/room/{room}/status', [RoomController::class, 'updateStatus'])->name('room.update_status');
            Route::post('/room/{room}/delete', [RoomController::class, 'destroy'])->name('room.delete');

            Route::resource('/schedule', ScheduleController::class);
             Route::post('/schedule/{schedule}/status', [ScheduleController::class, 'updateStatus'])->name('schedule.update_status');
            Route::post('/schedule/{schedule}/delete', [ScheduleController::class, 'destroy'])->name('schedule.delete');

            Route::resource('/course', CourseController::class)->except('destroy');;
            Route::post('/course/{course}/status', [CourseController::class, 'updateStatus'])->name('course.update_status');
            Route::post('/course/{course}/delete', [CourseController::class, 'destroy'])->name('course.delete');
        });
    });
});

Route::prefix('/lecturer')->name('lecturer.')->group(function () {
    Route::prefix('/tasks')->name('task.')->group(function () {
        Route::get('/tasks-today', [TaskController::class, 'tasksToday'])->name('tasks_today'); // View tasks for today
        Route::resource('/', TaskController::class)->except('destroy');
        Route::post('/{task}/status', [TaskController::class, 'updateStatus'])->name('update_status');
        Route::post('/{task}/delete', [TaskController::class, 'destroy'])->name('delete');
    });
});

Route::name('student.')->group(function () {
    Route::get('/tasks-today', [TaskController::class, 'tasksToday'])->name('tasks_today');
});

require __DIR__ . '/auth.php';
