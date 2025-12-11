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
        // 1. Ambil daftar produk untuk dropdown filter
        $produks_list = Produk::orderBy('Nama_Produk')->get();

        // 2. Ambil input filter dari URL
        $tgl_mulai = $request->query('tgl_mulai');
        $tgl_selesai = $request->query('tgl_selesai');
        $produk_id = $request->query('produk_id'); 

        // 3. Mulai Query dasar dengan relasi User (Kasir)
        // Kita juga bisa eager load 'details' jika ingin menampilkan jumlah item di tabel depan
        $query = Transaksi::with(['user']);

        // 4. Terapkan Filter Tanggal
        if ($tgl_mulai) {
            $query->whereDate('Tanggal', '>=', $tgl_mulai);
        }
        if ($tgl_selesai) {
            $query->whereDate('Tanggal', '<=', $tgl_selesai);
        }

        // 5. Terapkan Filter Produk (Menggunakan whereHas)
        // Ini akan mencari transaksi yang MEMILIKI detail produk tertentu
        if ($produk_id) {
            $query->whereHas('details', function($q) use ($produk_id) {
                $q->where('ID_Produk', $produk_id);
            });
        }

        // 6. Eksekusi Query dengan Pagination
        // Gunakan appends agar parameter filter tidak hilang saat klik halaman 2, 3, dst.
        $transaksis = $query->orderBy('Tanggal', 'desc')
            ->paginate(10)
            ->appends([
                'tgl_mulai' => $tgl_mulai,
                'tgl_selesai' => $tgl_selesai,
                'produk_id' => $produk_id
            ]);

        // 7. Kirim data ke View
        return view('transaksi.index', [
            'transaksis'    => $transaksis,
            'produks_list'  => $produks_list, 
            'tgl_mulai'     => $tgl_mulai,
            'tgl_selesai'   => $tgl_selesai,
            'produk_filter' => $produk_id 
        ]);
    }

    /**
     * Menampilkan detail satu transaksi
     */
    public function show(string $id)
    {
        // Menggunakan relasi 'details' (bukan detailTransaksi) dan 'produk'
        $transaksi = Transaksi::with(['user', 'details.produk'])
                        ->findOrFail($id);
        
        return view('transaksi.show', ['transaksi' => $transaksi]);
    }

    /**
     * Menampilkan form import
     */
    public function showImportForm()
    {
        return view('transaksi.import');
    }

    /**
     * Memproses import file Excel/CSV
     */
    public function processImport(Request $request)
    {
        // 1. Validasi file
        $request->validate([
            'file_transaksi' => 'required|mimes:xls,xlsx,csv,txt' 
        ]);

        try {
            // 2. Import file menggunakan Maatwebsite Excel
            Excel::import(new TransaksiImport, $request->file('file_transaksi'));
            
            // 3. Redirect kembali dengan pesan sukses
            return redirect(route('transaksi.index'))->with('success', 'Data transaksi berhasil di-import!');

        } catch (\Exception $e) {
            // 4. Jika error (misal format salah atau data duplikat)
            return back()->withErrors(['error' => 'Import gagal: ' . $e->getMessage()]);
        }
    }
}