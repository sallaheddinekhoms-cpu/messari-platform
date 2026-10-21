<?php

use App\Http\Controllers\Api\PlayerApiController;
use App\Http\Controllers\PlayerPassportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - منصة «مساري» (MASARI) - الرابطة الجهوية لكرة القدم بالبليدة
|--------------------------------------------------------------------------
*/

Route::get('/', [PlayerPassportController::class, 'index'])->name('home');
Route::get('/passport/{uuid}', [PlayerPassportController::class, 'show'])->name('passport.show');
Route::get('/competitions', [PlayerPassportController::class, 'competitions'])->name('competitions.index');
Route::get('/media', [\App\Http\Controllers\MediaController::class, 'index'])->name('media.index');
Route::get('/search', [PlayerPassportController::class, 'search'])->name('players.search');

// RESTful API endpoints
Route::prefix('api/v1')->group(function () {
    Route::get('/players/{uuid}', [PlayerApiController::class, 'getPlayerByUuid'])->name('api.players.show');
});
