<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;


// 1. Import controller
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PelangganController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransaksiController; 

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

// 6. Rute KELOLA TRANSAKSI
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    
// HAPUS RUTE CREATE/IMPORT
    // Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    // Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');

    // TAMBAHKAN RUTE BARU UNTUK DETAIL
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');

});