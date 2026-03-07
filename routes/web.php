<?php

use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\AuthPageController;
use App\Http\Controllers\Web\ClientPortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthPageController::class, 'index'])->name('home');
Route::post('/register', [AuthPageController::class, 'register'])->name('register');
Route::post('/login', [AuthPageController::class, 'login'])->name('login');
Route::post('/logout', [AuthPageController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/client', [ClientPortalController::class, 'index'])->name('client.portal');
    Route::post('/client/flashcards/{flashcard}/progress', [ClientPortalController::class, 'updateProgress'])->name('client.progress.update');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/users', [AdminDashboardController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');

    Route::post('/decks', [AdminDashboardController::class, 'storeDeck'])->name('decks.store');
    Route::put('/decks/{deck}', [AdminDashboardController::class, 'updateDeck'])->name('decks.update');
    Route::delete('/decks/{deck}', [AdminDashboardController::class, 'destroyDeck'])->name('decks.destroy');

    Route::post('/flashcards', [AdminDashboardController::class, 'storeFlashcard'])->name('flashcards.store');
    Route::put('/flashcards/{flashcard}', [AdminDashboardController::class, 'updateFlashcard'])->name('flashcards.update');
    Route::delete('/flashcards/{flashcard}', [AdminDashboardController::class, 'destroyFlashcard'])->name('flashcards.destroy');
});
