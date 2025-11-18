<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $tgl_mulai = $request->input('tgl_mulai');
        $tgl_selesai = $request->input('tgl_selesai');
        $produk_filter = $request->input('produk_id');

        if (!$tgl_mulai && !$tgl_selesai) {
            $tgl_mulai = now()->subDays(6)->toDateString();
            $tgl_selesai = now()->toDateString();
        }

        $produks_list = DB::table('produk')
            ->select('ID_Produk', 'Nama_Produk')
            ->orderBy('Nama_Produk')
            ->get();

        $queryStatistik = DB::table('transaksi as t')
            ->join('detail_transaksi as dt', 't.ID_Transaksi', '=', 'dt.ID_Transaksi');

        if ($produk_filter) {
            $queryStatistik->where('dt.ID_Produk', $produk_filter);
        }

        $queryStatistik->whereBetween(DB::raw('DATE(t.Tanggal)'), [$tgl_mulai, $tgl_selesai]);

        $statistik = $queryStatistik
            ->select(
                DB::raw('SUM(t.TotalHarga) as total_pendapatan'),
                DB::raw('COUNT(DISTINCT t.ID_Transaksi) as total_transaksi')
            )
            ->first();

        $topProduk = DB::table('produk as p')
            ->join('detail_transaksi as dt', 'p.ID_Produk', '=', 'dt.ID_Produk')
            ->join('transaksi as t', 'dt.ID_Transaksi', '=', 't.ID_Transaksi')
            ->whereBetween(DB::raw('DATE(t.Tanggal)'), [$tgl_mulai, $tgl_selesai])
            ->select('p.ID_Produk', 'p.Nama_Produk', DB::raw('SUM(dt.Jumlah_Produk) as total_terjual'))
            ->groupBy('p.ID_Produk', 'p.Nama_Produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $topPelanggan = DB::table('pelanggan as pl')
            ->join('transaksi as t', 'pl.ID_Pelanggan', '=', 't.ID_Pelanggan')
            ->join('detail_transaksi as dt', 't.ID_Transaksi', '=', 'dt.ID_Transaksi')
            ->where('pl.is_member', 1)
            ->whereBetween(DB::raw('DATE(t.Tanggal)'), [$tgl_mulai, $tgl_selesai]);

        if ($produk_filter) {
            $topPelanggan->where('dt.ID_Produk', $produk_filter);
        }

        $topPelanggan = $topPelanggan
            ->select('pl.ID_Pelanggan', 'pl.Nama_Pelanggan', DB::raw('COUNT(t.ID_Transaksi) as total_pembelian'))
            ->groupBy('pl.ID_Pelanggan', 'pl.Nama_Pelanggan')
            ->orderByDesc('total_pembelian')
            ->limit(5)
            ->get();

        $queryGrafik = DB::table('transaksi as t')
            ->leftJoin('detail_transaksi as dt', 't.ID_Transaksi', '=', 'dt.ID_Transaksi')
            ->whereBetween(DB::raw('DATE(t.Tanggal)'), [$tgl_mulai, $tgl_selesai]);

        if ($produk_filter) {
            $queryGrafik->where('dt.ID_Produk', $produk_filter);
        }

        $grafikTransaksi = $queryGrafik
            ->select(
                DB::raw('DATE(t.Tanggal) as tanggal'),
                DB::raw('COUNT(DISTINCT t.ID_Transaksi) as total_transaksi')
            )
            ->groupBy(DB::raw('DATE(t.Tanggal)'))
            ->orderBy('tanggal', 'ASC')
            ->get();

        return view('dashboard', [
            'namaUser' => $user->Nama_User,
            'roleUser' => $user->Role,
            'statistik' => $statistik,
            'topProduk' => $topProduk,
            'topPelanggan' => $topPelanggan,
            'grafikTransaksi_json' => $grafikTransaksi->toJson(),
            'produks_list' => $produks_list,
            'produk_filter' => $produk_filter,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
        ]);
    }

    private function getStatisticData($tgl_mulai, $tgl_selesai, $produk_filter)
    {
        return DB::table('transaksi as t')
            ->join('detail_transaksi as dt', 't.ID_Transaksi', '=', 'dt.ID_Transaksi')
            ->when($produk_filter, fn($q) => $q->where('dt.ID_Produk', $produk_filter))
            ->when($tgl_mulai, fn($q) => $q->whereDate('t.Tanggal', '>=', $tgl_mulai))
            ->when($tgl_selesai, fn($q) => $q->whereDate('t.Tanggal', '<=', $tgl_selesai))
            ->select(
                DB::raw('SUM(t.TotalHarga) as total_pendapatan'),
                DB::raw('COUNT(DISTINCT t.ID_Transaksi) as total_transaksi')
            )
            ->first();
    }

    private function getTopProduk($tgl_mulai, $tgl_selesai)
    {
        return DB::table('produk as p')
            ->join('detail_transaksi as dt', 'p.ID_Produk', '=', 'dt.ID_Produk')
            ->join('transaksi as t', 'dt.ID_Transaksi', '=', 't.ID_Transaksi')
            ->when($tgl_mulai, fn($q) => $q->whereDate('t.Tanggal', '>=', $tgl_mulai))
            ->when($tgl_selesai, fn($q) => $q->whereDate('t.Tanggal', '<=', $tgl_selesai))
            ->select('p.ID_Produk', 'p.Nama_Produk', DB::raw('SUM(dt.Jumlah_Produk) as total_terjual'))
            ->groupBy('p.ID_Produk', 'p.Nama_Produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();
    }

    private function getTopPelanggan($tgl_mulai, $tgl_selesai)
    {
        return DB::table('pelanggan as pl')
            ->join('transaksi as t', 'pl.ID_Pelanggan', '=', 't.ID_Pelanggan')
            ->join('detail_transaksi as dt', 't.ID_Transaksi', '=', 'dt.ID_Transaksi')
            ->where('pl.is_member', 1)
            ->when($tgl_mulai, fn($q) => $q->whereDate('t.Tanggal', '>=', $tgl_mulai))
            ->when($tgl_selesai, fn($q) => $q->whereDate('t.Tanggal', '<=', $tgl_selesai))
            ->select('pl.ID_Pelanggan', 'pl.Nama_Pelanggan', DB::raw('COUNT(t.ID_Transaksi) as total_pembelian'))
            ->groupBy('pl.ID_Pelanggan', 'pl.Nama_Pelanggan')
            ->orderByDesc('total_pembelian')
            ->limit(5)
            ->get();
    }

    private function getGrafikTransaksi($tgl_mulai, $tgl_selesai, $produk_filter)
    {
        return DB::table('transaksi as t')
            ->join('detail_transaksi as dt', 't.ID_Transaksi', '=', 'dt.ID_Transaksi')
            ->when($produk_filter, fn($q) => $q->where('dt.ID_Produk', $produk_filter))
            ->when($tgl_mulai, fn($q) => $q->whereDate('t.Tanggal', '>=', $tgl_mulai))
            ->when($tgl_selesai, fn($q) => $q->whereDate('t.Tanggal', '<=', $tgl_selesai))
            ->select(
                DB::raw('DATE(t.Tanggal) as tanggal'),
                DB::raw('COUNT(DISTINCT t.ID_Transaksi) as total_transaksi')
            )
            ->groupBy(DB::raw('DATE(t.Tanggal)'))
            ->orderBy('tanggal', 'ASC')
            ->get();
    }


    public function exportPDF(Request $request)
    {
        $namaUser = Auth::user()->Nama_User;
        $roleUser = Auth::user()->Role;

        $tgl_mulai = $request->tgl_mulai;
        $tgl_selesai = $request->tgl_selesai;
        $produk_filter = $request->produk_id;

        $statistik = $this->getStatisticData($tgl_mulai, $tgl_selesai, $produk_filter);
        $topProduk = $this->getTopProduk($tgl_mulai, $tgl_selesai, $produk_filter);
        $topPelanggan = $this->getTopPelanggan($tgl_mulai, $tgl_selesai);
        $grafikTransaksi = $this->getGrafikTransaksi($tgl_mulai, $tgl_selesai, $produk_filter);

        Log::info("Receive chart base64, size: " . strlen($request->chart_image));
        if ($request->chart_image) {
            $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $request->chart_image);
            $imageData = base64_decode($base64);
            
            $filename = 'chart-' . time() . '.png';
            $path = storage_path('app/public/' . $filename);
            
            file_put_contents($path, $imageData);
            
            $chartImagePath = 'storage/' . $filename;
            Log::info("chartImagePath: " . $chartImagePath);
        }

        // View PDF
        $pdf = Pdf::loadView('exportPdfDashboard', [
            'namaUser'        => $namaUser,
            'roleUser'        => $roleUser,
            'statistik'       => $statistik,
            'topProduk'       => $topProduk,
            'topPelanggan'    => $topPelanggan,
            'grafikTransaksi' => $grafikTransaksi,
            'chartImagePath'  => $chartImagePath,
            'tgl_mulai'       => $tgl_mulai,
            'tgl_selesai'     => $tgl_selesai,
            'produk_filter'   => $produk_filter,
        ]);

        return $pdf->stream('Dashboard-SIDAC.pdf');
    }
}
