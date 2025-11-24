<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

// Import controller lainnya
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransaksiController;
use App\Models\Pelanggan;

// --------------------------------------------------------------------------
// Rute Publik (Bisa diakses tanpa login)
// --------------------------------------------------------------------------

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --------------------------------------------------------------------------
// Rute Terproteksi (Harus Login)
// Karena kita ubah Authenticate.php, yang belum login akan kena 403 Forbidden
// --------------------------------------------------------------------------

Route::middleware(['auth'])->group(function () {

    // 1. Rute Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/export/pdf', [DashboardController::class, 'exportPDF'])
        ->name('dashboard.export.pdf');

    // 2. Rute CRUD Produk
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    // Import Produk
    Route::get('/produk/import', [ProdukController::class, 'showImportForm'])->name('produk.import.form');
    Route::post('/produk/import', [ProdukController::class, 'processImport'])->name('produk.import.process');

    // 3. Rute CRUD Pelanggan 
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::get('/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
    Route::put('/pelanggan/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
    // Import Pelanggan
    Route::get('/pelanggan/import', [PelangganController::class, 'showImportForm'])->name('pelanggan.import.form');
    // Perbaikan: Gunakan PelangganController, bukan Model Pelanggan::class langsung di rute
    Route::post('/pelanggan/import', [PelangganController::class, 'processImport'])->name('pelanggan.import.process');

    // 4. Rute CRUD User
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    // Import User
    Route::get('/user/import', [UserController::class, 'showImportForm'])->name('user.import.form');
    Route::post('/user/import', [UserController::class, 'processImport'])->name('user.import.process');

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
