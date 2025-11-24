<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi; 
use App\Models\Produk; 
use App\Http\Controllers\Controller; 
use Maatwebsite\Excel\Facades\Excel; 
use App\Imports\TransaksiImport;      

class TransaksiController extends Controller
{
    /**
     * Menampilkan halaman daftar (Read) transaksi.
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
        $query = Transaksi::with(['user']);

        // Terapkan filter (jika ada)
        if ($tgl_mulai) {
            $query->whereDate('Tanggal', '>=', $tgl_mulai);
        }
        if ($tgl_selesai) {
            $query->whereDate('Tanggal', '<=', $tgl_selesai);
        }
        if ($produk_id) {
            // === PERBAIKAN DI SINI ===
            // Ganti 'detailTransaksi' menjadi 'details' (Sesuai Model Transaksi.php)
            $query->whereHas('details', function($q) use ($produk_id) {
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
            'produks_list' => $produks_list, 
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
        // === PERBAIKAN DI SINI JUGA ===
        // Ganti 'detailTransaksi' menjadi 'details'
        $transaksi = Transaksi::with(['user', 'details.produk'])
                        ->findOrFail($id);
        
        return view('transaksi.show', ['transaksi' => $transaksi]);
    }

    public function showImportForm()
    {
        return view('transaksi.import');
    }

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
            // 4. Jika error
            return back()->withErrors(['error' => 'Import gagal: ' . $e->getMessage()]);
        }
    }
}