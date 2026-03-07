<?php

use App\Http\Controllers\Api\Admin\DeckController as AdminDeckController;
use App\Http\Controllers\Api\Admin\FlashcardController as AdminFlashcardController;
use App\Http\Controllers\Api\Admin\StatisticsController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Client\DeckController as ClientDeckController;
use App\Http\Controllers\Api\Client\StudyProgressController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

Route::prefix('client')->middleware(['auth:api', 'role:client'])->group(function () {
    Route::get('/decks', [ClientDeckController::class, 'index']);
    Route::get('/decks/{deck}', [ClientDeckController::class, 'show']);
    Route::get('/progress', [StudyProgressController::class, 'index']);
    Route::put('/flashcards/{flashcard}/progress', [StudyProgressController::class, 'update']);
});

Route::prefix('admin')->middleware(['auth:api', 'role:admin'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('decks', AdminDeckController::class);
    Route::apiResource('flashcards', AdminFlashcardController::class);
    Route::get('/statistics', StatisticsController::class);
});
