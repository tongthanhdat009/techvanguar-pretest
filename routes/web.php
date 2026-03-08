<?php

use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\AuthPageController;
use App\Http\Controllers\Web\ClientPortalController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [AuthPageController::class, 'landing'])->name('home');

// Auth (guests only)
Route::middleware('guest:client')->group(function () {
    Route::get('/register', [AuthPageController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthPageController::class, 'register'])->name('register.store');
    Route::get('/login/client', [AuthPageController::class, 'showClientLogin'])->name('client.login');
    Route::post('/login/client', [AuthPageController::class, 'clientLogin'])->name('client.login.store');
});

Route::middleware('guest:admin')->group(function () {
    Route::get('/login/admin', [AuthPageController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/login/admin', [AuthPageController::class, 'adminLogin'])->name('admin.login.store');
});

// Logout
Route::match(['get', 'post'], '/logout', [AuthPageController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], '/admin/logout', [AuthPageController::class, 'logout'])->name('admin.logout');

// Client Portal
Route::middleware('auth:client')
    ->prefix('client')
    ->name('client.')
    ->group(function () {
        Route::get('/', [ClientPortalController::class, 'index'])->name('dashboard');
        Route::get('/my-decks', [ClientPortalController::class, 'myDecks'])->name('my-decks');
        Route::get('/community', [ClientPortalController::class, 'community'])->name('community');
        Route::get('/decks/create', [ClientPortalController::class, 'createDeck'])->name('decks.create');
        Route::get('/decks/{deck}', [ClientPortalController::class, 'showDeck'])->name('decks.show');
        Route::post('/decks', [ClientPortalController::class, 'storeDeck'])->name('decks.store');
        Route::put('/decks/{deck}', [ClientPortalController::class, 'updateDeck'])->name('decks.update');
        Route::delete('/decks/{deck}', [ClientPortalController::class, 'destroyDeck'])->name('decks.destroy');
        Route::post('/decks/{deck}/copy', [ClientPortalController::class, 'copyDeck'])->name('decks.copy');
        Route::post('/decks/{deck}/review', [ClientPortalController::class, 'storeReview'])->name('decks.review');
        Route::post('/decks/{deck}/flashcards', [ClientPortalController::class, 'storeFlashcard'])->name('decks.flashcards.store');
        Route::put('/flashcards/{flashcard}', [ClientPortalController::class, 'updateFlashcard'])->name('flashcards.update');
        Route::delete('/flashcards/{flashcard}', [ClientPortalController::class, 'destroyFlashcard'])->name('flashcards.destroy');
        Route::get('/study/{deck}', [ClientPortalController::class, 'studyDeck'])->name('decks.study');
        Route::get('/study', [ClientPortalController::class, 'studyAll'])->name('study.all');
        Route::post('/study/progress', [ClientPortalController::class, 'updateProgress'])->name('study.progress');
        Route::get('/profile', [ClientPortalController::class, 'profile'])->name('profile');
        Route::put('/profile', [ClientPortalController::class, 'updateProfile'])->name('profile.update');
        Route::get('/decks/{deck}/export', [ClientPortalController::class, 'exportDeck'])->name('decks.export');
        Route::post('/decks/{deck}/import', [ClientPortalController::class, 'importDeck'])->name('decks.import');
        Route::match(['get', 'post'], '/logout', [AuthPageController::class, 'logout'])->name('logout');
    });

// Admin Portal
Route::middleware('auth:admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'overview'])->name('overview');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminDashboardController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminDashboardController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminDashboardController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');
        Route::patch('/users/{user}/role', [AdminDashboardController::class, 'toggleUserRole'])->name('users.toggle-role');
        Route::patch('/users/{user}/status', [AdminDashboardController::class, 'toggleUserStatus'])->name('users.toggle-status');
        Route::get('/decks', [AdminDashboardController::class, 'decks'])->name('decks');
        Route::get('/decks/create', [AdminDashboardController::class, 'createDeck'])->name('decks.create');
        Route::get('/decks/{deck}', [AdminDashboardController::class, 'showDeck'])->name('decks.show');
        Route::get('/decks/{deck}/edit', [AdminDashboardController::class, 'editDeck'])->name('decks.edit');
        Route::get('/reviews', [AdminDashboardController::class, 'reviews'])->name('reviews');
        Route::delete('/reviews/{review}', [AdminDashboardController::class, 'destroyReview'])->name('reviews.destroy');
        Route::post('/decks', [AdminDashboardController::class, 'storeDeck'])->name('decks.store');
        Route::put('/decks/{deck}', [AdminDashboardController::class, 'updateDeck'])->name('decks.update');
        Route::patch('/decks/{deck}/toggle', [AdminDashboardController::class, 'toggleDeckStatus'])->name('decks.toggle');
        Route::delete('/decks/{deck}', [AdminDashboardController::class, 'destroyDeck'])->name('decks.destroy');
        Route::post('/decks/{deck}/flashcards', [AdminDashboardController::class, 'storeFlashcard'])->name('decks.flashcards.store');
        Route::put('/flashcards/{flashcard}', [AdminDashboardController::class, 'updateFlashcard'])->name('flashcards.update');
        Route::delete('/flashcards/{flashcard}', [AdminDashboardController::class, 'destroyFlashcard'])->name('flashcards.destroy');
        Route::get('/decks/{deck}/export', [AdminDashboardController::class, 'exportDeck'])->name('decks.export');
        Route::post('/decks/{deck}/import', [AdminDashboardController::class, 'importDeck'])->name('decks.import');
    });