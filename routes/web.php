<?php

use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\AuthPageController;
use App\Http\Controllers\Web\ClientPortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthPageController::class, 'landing'])->name('home');

// Client authentication routes (client guest only)
Route::middleware(['guest:client'])->group(function () {
    Route::get('/login/client', [AuthPageController::class, 'showClientLogin'])->name('client.login');
    Route::post('/login/client', [AuthPageController::class, 'clientLogin'])->name('client.login.attempt');
    Route::get('/register', [AuthPageController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthPageController::class, 'register'])->name('register.store');
});

// Admin authentication routes (admin guest only)
Route::middleware(['guest:admin'])->group(function () {
    Route::get('/login/admin', [AuthPageController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/login/admin', [AuthPageController::class, 'adminLogin'])->name('admin.login.attempt');
});

// Client routes with separate session
Route::middleware(['auth:client'])->group(function () {
    Route::match(['get', 'post'], '/logout', [AuthPageController::class, 'logout'])->name('client.logout');

    Route::get('/client', [ClientPortalController::class, 'index'])->name('client.portal');
    Route::get('/client/dashboard', [ClientPortalController::class, 'index'])->name('client.dashboard');
    Route::get('/client/study', [ClientPortalController::class, 'studyAll'])->name('client.study.all');
    Route::get('/client/profile', [ClientPortalController::class, 'profile'])->name('client.profile');
    Route::put('/client/profile', [ClientPortalController::class, 'updateProfile'])->name('client.profile.update');

    Route::post('/client/decks', [ClientPortalController::class, 'storeDeck'])->name('client.decks.store');
    Route::post('/client/decks/import', [ClientPortalController::class, 'importDeck'])->name('client.decks.import');
    Route::get('/client/decks/{deck}', [ClientPortalController::class, 'showDeck'])->name('client.decks.show');
    Route::put('/client/decks/{deck}', [ClientPortalController::class, 'updateDeck'])->name('client.decks.update');
    Route::delete('/client/decks/{deck}', [ClientPortalController::class, 'destroyDeck'])->name('client.decks.destroy');
    Route::post('/client/decks/{deck}/copy', [ClientPortalController::class, 'copyDeck'])->name('client.decks.copy');
    Route::post('/client/decks/{deck}/reviews', [ClientPortalController::class, 'storeReview'])->name('client.decks.reviews.store');
    Route::get('/client/decks/{deck}/study', [ClientPortalController::class, 'studyDeck'])->name('client.decks.study');
    Route::get('/client/decks/{deck}/export', [ClientPortalController::class, 'exportDeck'])->name('client.decks.export');
    Route::post('/client/decks/{deck}/flashcards', [ClientPortalController::class, 'storeFlashcard'])->name('client.flashcards.store');

    Route::put('/client/flashcards/{flashcard}', [ClientPortalController::class, 'updateFlashcard'])->name('client.flashcards.update');
    Route::delete('/client/flashcards/{flashcard}', [ClientPortalController::class, 'destroyFlashcard'])->name('client.flashcards.destroy');
    Route::post('/client/flashcards/{flashcard}/progress', [ClientPortalController::class, 'updateProgress'])->name('client.progress.update');
});

// Admin routes with separate session
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::match(['get', 'post'], '/logout', [AuthPageController::class, 'logout'])->name('logout');

    // Overview - Dashboard stats only
    Route::get('/', [AdminDashboardController::class, 'overview'])->name('overview');

    // Users Management
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::post('/users', [AdminDashboardController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');

    // Decks Management
    Route::get('/decks', [AdminDashboardController::class, 'decks'])->name('decks');
    Route::post('/decks', [AdminDashboardController::class, 'storeDeck'])->name('decks.store');
    Route::post('/decks/import', [AdminDashboardController::class, 'importDeck'])->name('decks.import');
    Route::put('/decks/{deck}', [AdminDashboardController::class, 'updateDeck'])->name('decks.update');
    Route::delete('/decks/{deck}', [AdminDashboardController::class, 'destroyDeck'])->name('decks.destroy');
    Route::get('/decks/{deck}/export', [AdminDashboardController::class, 'exportDeck'])->name('decks.export');

    // Flashcards
    Route::post('/flashcards', [AdminDashboardController::class, 'storeFlashcard'])->name('flashcards.store');
    Route::put('/flashcards/{flashcard}', [AdminDashboardController::class, 'updateFlashcard'])->name('flashcards.update');
    Route::delete('/flashcards/{flashcard}', [AdminDashboardController::class, 'destroyFlashcard'])->name('flashcards.destroy');

    // Reviews Moderation
    Route::get('/reviews', [AdminDashboardController::class, 'reviews'])->name('reviews');
    Route::delete('/reviews/{review}', [AdminDashboardController::class, 'destroyReview'])->name('reviews.destroy');
});
