<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

Route::get('/health', fn() => ['ok' => true]);

Route::prefix('v1')->group(function () {
    Route::apiResource('books', BookController::class)
        ->parameters(['books' => 'id']);

    Route::apiResource('users', UserController::class)
        ->parameters(['users' => 'id']);

    Route::apiResource('loans', LoanController::class)
        ->parameters(['loans' => 'id'])
        ->only(['index', 'store', 'show']);

    Route::post('loans/{id}/return', [LoanController::class, 'return']);
    
    Route::prefix('stats')->group(function () {
        Route::get('/kpi', [StatsController::class, 'kpi']);
        Route::get('/top-books', [StatsController::class, 'topBooks']);
        Route::get('/loans-series', [StatsController::class, 'loansSeries']);
        Route::get('/genre-distribution', [StatsController::class, 'genreDistribution']);
        Route::get('/top-users', [StatsController::class, 'topUsers']);
        Route::get('/overdues', [StatsController::class, 'overdues']);
    });
});
