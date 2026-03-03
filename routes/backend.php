<?php

use App\Http\Controllers\backend\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('backend.dashboard');
})->name('dashboard');
Route::resource('users', UserController::class);
