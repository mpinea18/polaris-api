<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DroneHistoryController;

// Rutas públicas
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

// Catálogo de modelos (público)
Route::get('/drone-models', [App\Http\Controllers\DroneModelController::class, 'index']);
Route::get('/drone-models/marcas', [App\Http\Controllers\DroneModelController::class, 'marcas']);
Route::get('/drone-models/{id}', [App\Http\Controllers\DroneModelController::class, 'show']);

// Settings (público)
Route::get('/settings/{key}', [App\Http\Controllers\SettingsController::class, 'get']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/me', [App\Http\Controllers\AuthController::class, 'me']);

    // Citas
    Route::get('/appointments', [App\Http\Controllers\AppointmentController::class, 'index']);
    Route::post('/appointments', [App\Http\Controllers\AppointmentController::class, 'store']);
    Route::patch('/appointments/{id}/status', [App\Http\Controllers\AppointmentController::class, 'updateStatus']);

    // Drones
    Route::get('/drones', [App\Http\Controllers\DroneController::class, 'index']);
    Route::post('/drones', [App\Http\Controllers\DroneController::class, 'store']);
    Route::patch('/drones/{id}/status', [App\Http\Controllers\DroneController::class, 'updateStatus']);
    Route::delete('/drones/{id}', [App\Http\Controllers\DroneController::class, 'destroy']);

    // Reportes
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index']);
    Route::post('/reports', [App\Http\Controllers\ReportController::class, 'store']);
    Route::get('/drones/{id}/reports', [App\Http\Controllers\ReportController::class, 'byDrone']);

    // Usuarios (admin/superadmin)
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index']);
    Route::patch('/users/{id}', [App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy']);

    // Gestión catálogo modelos (superadmin)
    Route::post('/drone-models', [App\Http\Controllers\DroneModelController::class, 'store']);
    Route::put('/drone-models/{id}', [App\Http\Controllers\DroneModelController::class, 'update']);
    Route::delete('/drone-models/{id}', [App\Http\Controllers\DroneModelController::class, 'destroy']);

    // Garantías
    Route::get('/warranties', [App\Http\Controllers\WarrantyController::class, 'index']);
    Route::post('/warranties', [App\Http\Controllers\WarrantyController::class, 'store']);
    Route::post('/warranties/{id}/approve', [App\Http\Controllers\WarrantyController::class, 'approve']);
    Route::post('/warranties/{id}/deny', [App\Http\Controllers\WarrantyController::class, 'deny']);
    Route::patch('/warranties/{id}/status', [App\Http\Controllers\WarrantyController::class, 'updateStatus']);

    // Historia clínica global UAS
    Route::post('/drone-history', [DroneHistoryController::class, 'store']);
    Route::get('/drone-history/{droneId}', [DroneHistoryController::class, 'getByDrone']);

    // Settings (solo superadmin)
    Route::post('/settings/upload-video', [App\Http\Controllers\SettingsController::class, 'uploadVideo']);
    Route::post('/settings/{key}', [App\Http\Controllers\SettingsController::class, 'set']);
});