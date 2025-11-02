<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;


// 1. Import controller
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PelangganController; 
use App\Http\Controllers\UserController;

// Rute Autentikasi...
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// 2. Grup untuk halaman yang dilindungi
Route::middleware(['auth'])->group(function () {
    
    // Rute Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // 3. Rute CRUD Produk
    Route::resource('produk', ProdukController::class);

    // 4. Rute CRUD Pelanggan 
    Route::resource('pelanggan', PelangganController::class);

    //  5. Rute CRUD User
    Route::resource('user', UserController::class);

});