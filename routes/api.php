<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventMediaController;
use App\Http\Controllers\PartnerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'send']);

// Password Reset Routes
Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'resetPassword'])->name('password.update');

// Settings (Public Read)
Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index']);

// Public List Routes
Route::get('/banners', [\App\Http\Controllers\BannerController::class, 'index']);
Route::get('/banners/{banner}', [\App\Http\Controllers\BannerController::class, 'show']);

Route::get('/events', [\App\Http\Controllers\EventController::class, 'index']);
Route::get('/events/{event}', [\App\Http\Controllers\EventController::class, 'show']);

Route::get('/artists', [\App\Http\Controllers\ArtistController::class, 'index']);
Route::get('/artists/{artist}', [\App\Http\Controllers\ArtistController::class, 'show']);

Route::get('/partners', [\App\Http\Controllers\PartnerController::class, 'index']);
Route::get('/partners/{partner}', [\App\Http\Controllers\PartnerController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me']);
    
    // Dashboard & Activities
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);
    Route::get('/activities', [\App\Http\Controllers\ActivityController::class, 'index']);

    // Settings (Protected Write)
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'store']);

    Route::apiResource('banners', BannerController::class)->except(['index', 'show']);
    Route::apiResource('artists', ArtistController::class)->except(['index', 'show']);
    Route::apiResource('events', EventController::class)->except(['index', 'show']);
    Route::apiResource('partners', PartnerController::class)->except(['index', 'show']);

    // Access Management Modules
    Route::apiResource('users', \App\Http\Controllers\UserController::class);
    Route::apiResource('roles', \App\Http\Controllers\RoleController::class);
    Route::apiResource('permissions', \App\Http\Controllers\PermissionController::class);

    Route::post('events/{event}/media', [EventMediaController::class, 'store'])->name('events.media.store');
    Route::delete('events/{event}/media/{media}', [EventMediaController::class, 'destroy'])->name('events.media.destroy');
});
