<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin-home', [HomeController::class, 'adminHome'])->name('admin.home');
});
Route::middleware(['auth', 'role:super-admin'])->group(function () {
    Route::get('/super-home', [HomeController::class, 'superHome'])->name('super.home');
});
