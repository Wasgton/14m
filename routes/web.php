<?php

use Illuminate\Support\Facades\Route;

// Backoffice SPA — must come first to take priority
Route::get('/painel/{any?}', function () {
    return view('app');
})->where('any', '.*');

// Website SPA — serves the public site
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');
