<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\InfaqReportController;
use App\Http\Controllers\ZakatReportController;
use App\Http\Controllers\QurbanAnimalController;
use App\Http\Controllers\QurbanOwnerController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']); // Tambahan standar login

Route::prefix('public')->group(function () {
    Route::get('announcements', [AnnouncementController::class, 'index']);
    Route::get('events', [EventController::class, 'index']);
    Route::get('infaq', [InfaqReportController::class, 'index']);
    Route::get('zakat', [ZakatReportController::class, 'index']);
    Route::get('qurban', [QurbanAnimalController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'dev_auth'])->group(function () {

    // User Context (Melihat Profile)
    Route::get('me', function (Request $request) {
        return response()->json([
            'mode' => 'auth-session',
            'user' => $request->user(),
        ]);
    });

    /*
    |----------------------------------------------------------------------
    | AKSES: WARGA & PENGURUS
    |----------------------------------------------------------------------
    */
    Route::middleware('role:pengurus,warga')->group(function () {
        Route::post('registrations', [RegistrationController::class, 'store']);
        Route::post('events/confirm', [RegistrationController::class, 'confirmPresence']);
    });

    /*
    |----------------------------------------------------------------------
    | AKSES: KHUSUS PENGURUS
    |----------------------------------------------------------------------
    */
    Route::middleware('role:pengurus')->group(function () {

        // Kelola Announcements
        Route::apiResource('announcements', AnnouncementController::class)->except(['index', 'show']);

        // Kelola Events
        Route::post('events', [EventController::class, 'store']);
        Route::put('events/{id}', [EventController::class, 'update']);
        Route::delete('events/{id}', [EventController::class, 'destroy']);

        // Kelola Finance Reports
        Route::post('infaq', [InfaqReportController::class, 'store']);
        Route::post('zakat', [ZakatReportController::class, 'store']);

        // Kelola Qurban
        Route::post('qurban', [QurbanAnimalController::class, 'store']);
        Route::apiResource('qurban-owners', QurbanOwnerController::class);

    });
});