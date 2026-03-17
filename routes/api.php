<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DroneController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

// ── Rutas públicas ─────────────────────────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ── Rutas protegidas (requieren token) ─────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Citas
    Route::get('/appointments',                        [AppointmentController::class, 'index']);
    Route::post('/appointments',                       [AppointmentController::class, 'store']);
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);

    // Drones
    Route::get('/drones',                          [DroneController::class, 'index']);
    Route::post('/drones',                         [DroneController::class, 'store']);
    Route::patch('/drones/{drone}/status',         [DroneController::class, 'updateStatus']);

    // Reportes / Historia clínica
    Route::get('/reports',                         [ReportController::class, 'index']);
    Route::post('/reports',                        [ReportController::class, 'store']);
    Route::get('/drones/{drone}/reports',          [ReportController::class, 'droneHistory']);

    // Usuarios (solo admin y superadmin)
    Route::get('/users',                           [UserController::class, 'index']);
    Route::patch('/users/{user}/role',             [UserController::class, 'changeRole']);
    Route::patch('/users/{user}/toggle-status',    [UserController::class, 'toggleStatus']);
    Route::delete('/users/{user}',                 [UserController::class, 'destroy']);
});
