<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi; 
use App\Http\Controllers\Controller; 
// Hapus 'Excel', 'Str', 'DB', 'Auth', 'Carbon' jika tidak dipakai lagi

class TransaksiController extends Controller
{
    /**
     * Menampilkan halaman daftar (Read) transaksi.
     */
    public function index()
    {
        // Ambil data transaksi, TAPI juga ambil data relasinya (user & pelanggan)
        $transaksis = Transaksi::with(['user', 'pelanggan'])
                        ->orderBy('Tanggal', 'desc') 
                        ->get();

        // Kirim data ke view
        return view('transaksi.index', ['transaksis' => $transaksis]);
    }

    /**
     * FUNGSI BARU: Menampilkan detail satu transaksi
     */
    public function show(string $id)
    {
        // 1. Cari transaksi berdasarkan ID
        // 2. Kita pakai 'with()' untuk mengambil semua data terkait:
        //    - user (kasir)
        //    - pelanggan
        //    - detailTransaksi (daftar item)
        //    - detailTransaksi.produk (nama produk di dalam daftar item)
        $transaksi = Transaksi::with(['user', 'pelanggan', 'detailTransaksi.produk'])
                        ->findOrFail($id);

        // 3. Kirim data transaksi lengkap itu ke view 'show'
        return view('transaksi.show', ['transaksi' => $transaksi]);
    }

    // HAPUS SEMUA FUNGSI LAIN (create, store, showImportForm, processImport)
}