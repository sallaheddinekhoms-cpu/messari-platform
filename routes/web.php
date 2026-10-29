<?php

use App\Http\Controllers\Api\PlayerApiController;
use App\Http\Controllers\ClubPortalController;
use App\Http\Controllers\DtrFormationController;
use App\Http\Controllers\PlayerPassportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - منصة «مساري» (MASARI) - الرابطة الجهوية لكرة القدم بالبليدة
|--------------------------------------------------------------------------
*/

Route::get('/', [PlayerPassportController::class, 'index'])->name('home');
Route::get('/competitions', [PlayerPassportController::class, 'competitions'])->name('competitions.index');
Route::get('/media', [\App\Http\Controllers\MediaController::class, 'index'])->name('media.index');
Route::get('/passport/{uuid}', [PlayerPassportController::class, 'show'])->name('passport.show');
Route::get('/search', [PlayerPassportController::class, 'search'])->name('players.search');

// DTR Coaching & Training System (المنظومة التكوينية الشاملة للمدربين - المديرية التقنية الجهوية)
Route::prefix('dtr')->name('dtr.')->group(function () {
    Route::get('/', [DtrFormationController::class, 'index'])->name('index');
    Route::get('/apply', [DtrFormationController::class, 'apply'])->name('apply');
    Route::post('/apply', [DtrFormationController::class, 'submitApplication'])->name('apply.submit');
    Route::get('/login', [DtrFormationController::class, 'showLogin'])->name('login');
    Route::post('/login', [DtrFormationController::class, 'login'])->name('login.post');
    Route::post('/logout', [DtrFormationController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DtrFormationController::class, 'candidateDashboard'])->name('dashboard');
        Route::post('/application/{id}/update-reserve', [DtrFormationController::class, 'updateReservation'])->name('application.update-reserve');
        Route::get('/material/{materialId}/application/{applicationId}', [DtrFormationController::class, 'downloadMaterial'])->name('material.download');
    });
});

// Club Portal Routes (بوابة حسابات الأندية الرسمية)
Route::prefix('club')->name('club.')->group(function () {
    Route::get('/login', [ClubPortalController::class, 'showLogin'])->name('login');
    Route::post('/login', [ClubPortalController::class, 'login'])->name('login.post');
    Route::post('/logout', [ClubPortalController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [ClubPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/roster/print/{category}', [ClubPortalController::class, 'printRoster'])->name('roster.print');
        Route::get('/match-sheet/print/{match_id}', [ClubPortalController::class, 'printMatchSheet'])->name('match-sheet.print');
    });
});

// RESTful API endpoints
Route::prefix('api/v1')->group(function () {
    Route::get('/players/{uuid}', [PlayerApiController::class, 'getPlayerByUuid'])->name('api.players.show');
});
