<?php

use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('backend.dashboard');
})->name('dashboard');
Route::resource('users', UserController::class);
Route::resource('roles', RoleController::class);
Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
Route::post('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');
