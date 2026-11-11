<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::middleware('api.token')->group(function () {
        Route::get('user', fn (Request $request) => $request->user()->only(['id', 'name', 'email', 'role']));
        Route::post('logout', [AuthController::class, 'logout']);

        Route::apiResource('students', StudentController::class)->except('destroy');
        Route::delete('students/{student}', [StudentController::class, 'destroy'])->middleware('admin')->name('students.destroy');
    });
});
