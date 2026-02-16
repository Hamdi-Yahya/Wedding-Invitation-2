<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventSettingsController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\ThemeSettingsController;
use App\Http\Controllers\WeddingDetailsController;
use App\Http\Controllers\WishController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InvitationController::class, 'index'])->name('home');
Route::get('/invite/{slug}', [InvitationController::class, 'personalInvite'])->name('invite');

Route::post('/api/rsvp', [RsvpController::class, 'store']);
Route::get('/api/wishes', [WishController::class, 'apiIndex']);
Route::post('/api/wishes', [WishController::class, 'store']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/event-settings', [EventSettingsController::class, 'index'])->name('dashboard.event-settings');
    Route::get('/guests', [GuestController::class, 'index'])->name('dashboard.guests');
    Route::get('/wishes', [WishController::class, 'index'])->name('dashboard.wishes');
    Route::get('/gallery', [GalleryController::class, 'index'])->name('dashboard.gallery');
    Route::get('/scanner', [CheckInController::class, 'index'])->name('dashboard.scanner');
    Route::get('/theme-settings', [ThemeSettingsController::class, 'index'])->name('dashboard.theme-settings');
    Route::get('/export', [ExportController::class, 'index'])->name('dashboard.export');
});

Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/guests', [GuestController::class, 'apiIndex']);
    Route::post('/guests', [GuestController::class, 'store']);
    Route::get('/guests/{id}', [GuestController::class, 'show']);
    Route::put('/guests/{id}', [GuestController::class, 'update']);
    Route::delete('/guests/{id}', [GuestController::class, 'destroy']);

    Route::put('/event-settings', [EventSettingsController::class, 'update']);
    Route::put('/theme-settings', [ThemeSettingsController::class, 'update']);

    Route::get('/wedding-details', [WeddingDetailsController::class, 'get']);
    Route::put('/wedding-details', [WeddingDetailsController::class, 'update']);

    Route::get('/gallery', [GalleryController::class, 'apiIndex']);
    Route::post('/gallery', [GalleryController::class, 'store']);
    Route::put('/gallery/{id}', [GalleryController::class, 'update']);
    Route::delete('/gallery/{id}', [GalleryController::class, 'destroy']);

    Route::post('/upload', [GalleryController::class, 'upload']);
    Route::post('/check-in', [CheckInController::class, 'processCheckIn']);

    Route::put('/wishes/approve-all', [WishController::class, 'approveAll']);
    Route::put('/wishes/{id}', [WishController::class, 'update']);
    Route::delete('/wishes/{id}', [WishController::class, 'destroy']);
});
