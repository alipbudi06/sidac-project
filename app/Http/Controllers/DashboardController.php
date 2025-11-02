<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller; // <-- INI ADALAH BARIS YANG HILANG

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

        // === Buat Query Dasar ===
        $queryStatistik = DB::table('transaksi');

        $queryTopProduk = DB::table('produk as p')
            ->join('detail_transaksi as dt', 'p.ID_Produk', '=', 'dt.ID_Produk')
            ->join('transaksi as t', 'dt.ID_Transaksi', '=', 't.ID_Transaksi')
            ->select('p.Nama_Produk', DB::raw('SUM(dt.Jumlah_Produk) as total_terjual'))
            ->groupBy('p.Nama_Produk');

        $queryTopPelanggan = DB::table('pelanggan as p')
            ->join('transaksi as t', 'p.ID_Pelanggan', '=', 't.ID_Pelanggan')
            ->select('p.Nama_Pelanggan', DB::raw('COUNT(t.ID_Transaksi) as total_transaksi'))
            ->groupBy('p.Nama_Pelanggan');

        $queryGrafik = DB::table('transaksi')
            ->select(
                DB::raw("strftime('%Y-%m', Tanggal) as bulan"),
                DB::raw('SUM(TotalHarga) as total')
            )
            ->groupBy('bulan');


        // === Terapkan Filter Tanggal JIKA ADA ===
        if ($tgl_mulai) {
            $queryStatistik->whereDate('Tanggal', '>=', $tgl_mulai);
            $queryTopProduk->whereDate('t.Tanggal', '>=', $tgl_mulai);
            $queryTopPelanggan->whereDate('t.Tanggal', '>=', $tgl_mulai);
            $queryGrafik->whereDate('Tanggal', '>=', $tgl_mulai);
        }

        if ($tgl_selesai) {
            $queryStatistik->whereDate('Tanggal', '<=', $tgl_selesai);
            $queryTopProduk->whereDate('t.Tanggal', '<=', $tgl_selesai);
            $queryTopPelanggan->whereDate('t.Tanggal', '<=', $tgl_selesai);
            $queryGrafik->whereDate('Tanggal', '<=', $tgl_selesai);
        }
        
        // === Eksekusi Query Setelah Filter Diterapkan ===
        
        $statistik = $queryStatistik->select(
                            DB::raw('SUM(TotalHarga) as total_pendapatan'),
                            DB::raw('COUNT(ID_Transaksi) as total_transaksi')
                        )
                        ->first();
        
        $topProduk = $queryTopProduk->orderBy('total_terjual', 'DESC')
                            ->limit(5)
                            ->get();

        $topPelanggan = $queryTopPelanggan->orderBy('total_transaksi', 'DESC')
                            ->limit(5)
                            ->get();

        $grafikPendapatan = $queryGrafik->orderBy('bulan', 'ASC')->get();
            
        // ========================================================
        
        // 6. Kirim semua data ke view
        return view('dashboard', [
            'namaUser' => $user->Nama_User,
            'roleUser' => $user->Role,
            'statistik' => $statistik,
            'topProduk' => $topProduk,
            'topPelanggan' => $topPelanggan,
            'grafikPendapatan_json' => $grafikPendapatan->toJson()
        ]);
    }
}