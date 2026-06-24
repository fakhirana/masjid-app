<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\InfaqReportController;
use App\Http\Controllers\ZakatReportController;
use App\Http\Controllers\QurbanAnimalController;
use App\Http\Controllers\QurbanOwnerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WargaVerificationController;
use App\Http\Controllers\InvitationCodeController;

/*
|--------------------------------------------------------------------------
| AUTH (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| PUBLIC (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::prefix('public')->group(function () {
    Route::get('dashboard/summary', [DashboardController::class, 'publicSummary']);
    Route::get('announcements', [AnnouncementController::class, 'index']);
    Route::get('infaq', [InfaqReportController::class, 'index']);
    Route::get('zakat', [ZakatReportController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED (Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'dev_auth'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | PROFILE & USER CONTEXT
    |----------------------------------------------------------------------
    */
    Route::get('me', [ProfileController::class, 'me']);
    Route::put('me', [ProfileController::class, 'update']);

    // TAMU ajukan verifikasi warga
    Route::post('warga-verification', [WargaVerificationController::class, 'store']);

    /*
    |----------------------------------------------------------------------
    | AKSES: WARGA & PENGURUS
    |----------------------------------------------------------------------
    */
    Route::middleware('role:warga,pengurus')->group(function () {
        Route::post('events/confirm', [AttendanceController::class, 'confirm']);
        Route::get('events', [EventController::class, 'index']);
        Route::get('events/{event}', [EventController::class, 'show']);
        // Route::post('login', [AuthController::class, 'login']);

    });

    /*
    |----------------------------------------------------------------------
    | AKSES: KHUSUS PENGURUS
    |----------------------------------------------------------------------
    */
    Route::middleware('role:pengurus')->group(function () {

        Route::get('dashboard/summary', [DashboardController::class, 'adminSummary']);

        // === WARGA VERIFICATION ===
        Route::get('warga-verification', [WargaVerificationController::class, 'index']);
        Route::post(
            'warga-verification/{verification}/approve',
            [WargaVerificationController::class, 'approve']
        );
        Route::post(
            'warga-verification/{verification}/reject',
            [WargaVerificationController::class, 'reject']
        );

        // === ANNOUNCEMENTS ===
        Route::apiResource('announcements', AnnouncementController::class)
            ->except(['index', 'show']);

        // === EVENTS ===
        Route::post('events', [EventController::class, 'store']);
        Route::put('events/{event}', [EventController::class, 'update']);
        Route::delete('events/{event}', [EventController::class, 'destroy']);
        Route::get('events/{event}/attendances', [EventController::class, 'attendances']);

        // === FINANCE & QURBAN ===
        Route::post('infaq', [InfaqReportController::class, 'store']);
        Route::post('zakat', [ZakatReportController::class, 'store']);
        Route::post('qurban', [QurbanAnimalController::class, 'store']);
        Route::apiResource('qurban-owners', QurbanOwnerController::class);

        // === INVITATION CODE (PENGURUS) ===
        Route::post('invitation-codes', [InvitationCodeController::class, 'generate']);
    });

    /*
    |----------------------------------------------------------------------
    | AKSES: WARGA (PAKAI INVITATION CODE)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:warga')->group(function () {
        Route::post(
            'invitation-codes/use',
            [InvitationCodeController::class, 'use']
        );
    });

});
