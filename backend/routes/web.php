<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('web')->group(function () {
    Route::get('/sanctum/csrf-cookie', function () {
        return response()->noContent();
    });

    Route::post('/login', [AuthController::class, 'login']);
});
