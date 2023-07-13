<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\EncounterController;

Route::middleware('api-key')->group(function () {
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::get('/patients', [PatientController::class, 'index']);
    Route::prefix('encounters')->group(function () {
        Route::get('/', [EncounterController::class, 'index']);
        Route::post('/', [EncounterController::class, 'store']);
    });
});

Route::get('/users/{uid}', [App\Http\Controllers\UserController::class, 'check']);
