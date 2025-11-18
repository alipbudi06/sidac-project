<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi; 
use App\Models\Produk; // <-- Import ini
use App\Http\Controllers\Controller; 
use Maatwebsite\Excel\Facades\Excel; // <-- Import ini
use App\Imports\TransaksiImport;      // <-- Import ini

class TransaksiController extends Controller
{
    /**
     * Menampilkan halaman daftar (Read) transaksi.
     * (Sudah diperbarui dengan filter dan $produks_list)
     */
    public function index(Request $request) 
    {
        // Ambil daftar produk untuk dropdown filter
        $produks_list = Produk::orderBy('Nama_Produk')->get();

        // Ambil semua input filter
        $tgl_mulai = $request->query('tgl_mulai');
        $tgl_selesai = $request->query('tgl_selesai');
        $produk_id = $request->query('produk_id'); 

        // Mulai query, dan ambil relasinya
        $query = Transaksi::with(['user', 'pelanggan']);

        // Terapkan filter (jika ada)
        if ($tgl_mulai) {
            $query->whereDate('Tanggal', '>=', $tgl_mulai);
        }
        if ($tgl_selesai) {
            $query->whereDate('Tanggal', '<=', $tgl_selesai);
        }
        if ($produk_id) {
            $query->whereHas('detailTransaksi', function($q) use ($produk_id) {
                $q->where('ID_Produk', $produk_id);
            });
        }

        // Eksekusi query
        $transaksis = $query->orderBy('Tanggal', 'desc')->paginate(10)
            ->appends([
                'tgl_mulai' => $tgl_mulai,
                'tgl_selesai' => $tgl_selesai,
                'produk_id' => $produk_id
            ]);


        // Kirim SEMUA data (termasuk $produks_list) ke view
        return view('transaksi.index', [
            'transaksis' => $transaksis,
            'produks_list' => $produks_list, // <-- Ini memperbaiki error sebelumnya
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
            'produk_filter' => $produk_id 
        ]);
    }

    /**
     * Menampilkan detail satu transaksi
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::with(['user', 'pelanggan', 'detailTransaksi.produk'])
                        ->findOrFail($id);
        return view('transaksi.show', ['transaksi' => $transaksi]);
    }

    // =============================================
    // === FUNGSI YANG HILANG (INI PERBAIKANNYA) ===
    // =============================================
    public function showImportForm()
    {
        return view('transaksi.import');
    }

    // =============================================
    // === FUNGSI YANG HILANG (INI PERBAIKANNYA) ===
    // =============================================
    public function processImport(Request $request)
    {
        // 1. Validasi file
        $request->validate([
            'file_transaksi' => 'required|mimes:xls,xlsx,csv,txt' 
        ]);

        try {
            // 2. Import file
            Excel::import(new TransaksiImport, $request->file('file_transaksi'));
            
            // 3. Redirect kembali dengan pesan sukses
            return redirect(route('transaksi.index'))->with('success', 'Data transaksi berhasil di-import!');

        } catch (\Exception $e) {
            // 4. Jika error (misal: header salah)
            return back()->withErrors(['error' => 'Import gagal: ' . $e->getMessage()]);
        }
    }
}