<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemorialsController;

// stub password reset route
Route::get('{token}', function () {
})->middleware('guest')->name('password.reset');

Route::get('memorial/{uuid}', [MemorialsController::class, 'show'])
    ->name('memorial');
