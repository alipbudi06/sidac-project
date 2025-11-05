<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller; 

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard.
     */
    public function index(Request $request)
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // 1. Ambil input filter dari URL
        $tgl_mulai = $request->input('tgl_mulai');
        $tgl_selesai = $request->input('tgl_selesai');
        
        // Ambil daftar semua produk untuk filter dropdown
        
        $produks_list = DB::table('produk')
            ->select('ID_Produk', 'Nama_Produk')
            ->orderBy('Nama_Produk', 'ASC')
            ->get();


        // === Query Statistik (Ini tetap dinamis berdasarkan filter) ===
        $queryStatistik = DB::table('transaksi');
        if ($tgl_mulai && $tgl_selesai) {
            $queryStatistik->whereBetween('Tanggal', [$tgl_mulai, $tgl_selesai]);
        } elseif ($tgl_mulai) {
            $queryStatistik->whereDate('Tanggal', '>=', $tgl_mulai);
        } elseif ($tgl_selesai) {
            $queryStatistik->whereDate('Tanggal', '<=', $tgl_selesai);
        } else {
            // kalau user belum pilih filter, tampilkan transaksi hari ini
            $queryStatistik->whereDate('Tanggal', now()->toDateString());
        }
        
        $statistik = $queryStatistik->select(
                            DB::raw('SUM(TotalHarga) as total_pendapatan'),
                            DB::raw('COUNT(ID_Transaksi) as total_transaksi')
                        )
                        ->first();
        
        // === Query Analitik (Membaca dari SQL View) ===

        // Query Top 5 Produk (Ini membaca v_top_produk)
        $topProduk = DB::table('v_top_produk')
            ->when($tgl_mulai, fn($q) => $q->whereDate('Tanggal', '>=', $tgl_mulai))
            ->when($tgl_selesai, fn($q) => $q->whereDate('Tanggal', '<=', $tgl_selesai))
            ->orderBy('total_terjual', 'DESC')
            ->limit(5)
            ->get();

        // ========================================================
        // === PERBAIKAN YANG ANDA MINTA ADA DI SINI ===
        // ========================================================
        // Query Top 5 Pelanggan diubah agar MEMBACA dari v_top_pelanggan,
        // yang datanya diambil dari kolom 'Frekuensi_Pembelian'.
        $topPelanggan = DB::table('v_top_pelanggan')
            ->when($tgl_mulai, fn($q) => $q->whereDate('Tanggal', '>=', $tgl_mulai))
            ->when($tgl_selesai, fn($q) => $q->whereDate('Tanggal', '<=', $tgl_selesai))
            ->orderBy('Frekuensi_Pembelian', 'DESC')
            ->limit(5)
            ->get();
        // ========================================================

        // Query Grafik Pendapatan (Ini membaca v_pendapatan_bulanan)
        $queryGrafik = DB::table('transaksi')
            ->select(
                DB::raw('DATE(Tanggal) as tanggal'),
                DB::raw('SUM(TotalHarga) as total_pendapatan')
            )
            ->groupBy(DB::raw('DATE(Tanggal)'))
            ->orderBy(DB::raw('DATE(Tanggal)'), 'ASC');

        if ($tgl_mulai) {
            $queryGrafik->whereDate('Tanggal', '>=', $tgl_mulai);
        }
        if ($tgl_selesai) {
            $queryGrafik->whereDate('Tanggal', '<=', $tgl_selesai);
        }

        $grafikPendapatan = $queryGrafik->get();

    
        // 6. Kirim semua data ke view
        return view('dashboard', [
            'namaUser' => $user->Nama_User,
            'roleUser' => $user->Role,
            'statistik' => $statistik,
            'topProduk' => $topProduk,
            'topPelanggan' => $topPelanggan, // <-- Sekarang datanya sinkron
            'grafikPendapatan_json' => $grafikPendapatan->toJson(),
            'produks_list' => $produks_list
        ]);
    }
}