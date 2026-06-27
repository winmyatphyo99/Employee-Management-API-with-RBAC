<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('permission:employee.view')->group(function () {

        Route::get('/employees', function () {
            return response()->json([
                'success' => true,
                'message' => 'You are allowed to view employees.'
            ]);
        });
    });
});
