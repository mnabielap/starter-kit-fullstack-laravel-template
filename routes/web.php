<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home / Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Auth Pages (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::get('/register', [AuthController::class, 'registerView'])->name('register');
    Route::get('/forgot-password', [AuthController::class, 'forgotPasswordView'])->name('password.request');
    Route::get('/reset-password', [AuthController::class, 'resetPasswordView'])->name('password.reset');
});

// User Management Pages (UI)
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::get('/users/edit', [UserController::class, 'edit'])->name('users.edit');