<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;


// 1. Import controller
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransaksiController;
use App\Models\Pelanggan;

// Rute Autentikasi...
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// 2. Grup untuk halaman yang dilindungi
Route::middleware(['auth'])->group(function () {

    // Rute Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/export/pdf', [DashboardController::class, 'exportPDF'])
        ->name('dashboard.export.pdf');

    // 3. Rute CRUD Produk
    // Route::resource('produk', ProdukController::class);
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    // Rute Import BARU
    Route::get('/produk/import', [ProdukController::class, 'showImportForm'])->name('produk.import.form');
    Route::post('/produk/import', [ProdukController::class, 'processImport'])->name('produk.import.process');

    // 4. Rute CRUD Pelanggan 
    // Route::resource('pelanggan', PelangganController::class);
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::get('/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
    Route::put('/pelanggan/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
    Route::get('/pelanggan/import', [PelangganController::class, 'showImportForm'])->name('pelanggan.import.form');
    Route::post('/pelanggan/import', [Pelanggan::class, 'processImport'])->name('pelanggan.import.process');

    //  5. Rute CRUD User
    // Route::resource('user', UserController::class);
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    // 6. Rute KELOLA TRANSAKSI
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');

    // HAPUS RUTE CREATE/IMPORT
    Route::get('/transaksi/import', [TransaksiController::class, 'showImportForm'])->name('transaksi.import.form');
    Route::post('/transaksi/import', [TransaksiController::class, 'processImport'])->name('transaksi.import.process');

    // TAMBAHKAN RUTE BARU UNTUK DETAIL
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::get('/pelanggan/import', [PelangganController::class, 'showImportForm'])->name('pelanggan.import.form');
    Route::post('/pelanggan/import', [PelangganController::class, 'processImport'])->name('pelanggan.import.process');
    Route::get('/produk/import', [ProdukController::class, 'showImportForm'])->name('produk.import.form');
    Route::post('/produk/import', [ProdukController::class, 'processImport'])->name('produk.import.process');
    Route::get('/user/import', [UserController::class, 'showImportForm'])->name('user.import.form');
    Route::post('/user/import', [UserController::class, 'processImport'])->name('user.import.process');
});
