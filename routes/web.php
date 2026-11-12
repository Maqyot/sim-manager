<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentMessageController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\SubjectController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('events', [EventController::class, 'index'])->name('events.index');
    Route::get('password', [AuthController::class, 'showChangePassword'])->name('password.edit');
    Route::put('password', [AuthController::class, 'updatePassword'])->name('password.update');

    Route::middleware('staff')->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
        Route::get('sessions', [SessionController::class, 'index'])->name('sessions.index');

        Route::resource('students', StudentController::class)->except('destroy');
        Route::resource('enrollments', EnrollmentController::class)->except(['show', 'destroy']);
        Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index');

        Route::middleware(EnsureAdmin::class)->group(function () {
            Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
            Route::delete('enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
            Route::resource('subjects', SubjectController::class)->except(['index', 'show']);
            Route::resource('events', EventController::class)->except(['index', 'show']);
            Route::get('admin/messages', [AdminMessageController::class, 'index'])->name('admin.messages.index');
            Route::get('admin/messages/{student}', [AdminMessageController::class, 'show'])->name('admin.messages.show');
            Route::post('admin/messages/{student}', [AdminMessageController::class, 'store'])->name('admin.messages.store');
        });
    });

    Route::middleware('student')->prefix('student')->name('student.')->group(function () {
        Route::get('dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('enrollments', [StudentPortalController::class, 'enrollments'])->name('enrollments');
        Route::get('messages', [StudentMessageController::class, 'index'])->name('messages');
        Route::post('messages', [StudentMessageController::class, 'store'])->name('messages.store');
    });
});
