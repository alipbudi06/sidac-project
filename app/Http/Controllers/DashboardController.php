<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Setup Filter
        $tgl_mulai = $request->input('tgl_mulai');
        $tgl_selesai = $request->input('tgl_selesai');
        $produk_filter = $request->input('produk_id');

        if (!$tgl_mulai && !$tgl_selesai) {
            $tgl_mulai = now()->subDays(6)->toDateString();
            $tgl_selesai = now()->toDateString();
        }

        $produks_list = DB::table('produk')->select('ID_Produk', 'Nama_Produk')->orderBy('Nama_Produk')->get();

        // 2. PANGGIL DATA
        $statistik = $this->getStatisticData($tgl_mulai, $tgl_selesai, $produk_filter);
        $topProduk = $this->getTopProduk($tgl_mulai, $tgl_selesai);
        $topPelanggan = $this->getTopPelanggan();
        $grafikTransaksi = $this->getGrafikTransaksi($tgl_mulai, $tgl_selesai, $produk_filter);
        $transaksiTerbaru = $this->getTransaksiTerbaru($tgl_mulai, $tgl_selesai, $produk_filter);
        
        // [GANTI] Data Tabel ke-3: Metode Pembayaran (Bukan Kasir lagi)
        $topMetode = $this->getTopMetodePembayaran($tgl_mulai, $tgl_selesai);

        // Data Chart
        $chartProdukLabel = $topProduk->pluck('Nama_Produk'); 
        $chartProdukData  = $topProduk->pluck('total_terjual');

        return view('dashboard', [
            'namaUser'      => $user->Nama_User,
            'roleUser'      => $user->Role,
            'statistik'     => $statistik,
            'topProduk'     => $topProduk,
            'topPelanggan'  => $topPelanggan,
            'topMetode'     => $topMetode, // Kirim data Metode Pembayaran
            'transaksiTerbaru' => $transaksiTerbaru,
            'grafikTransaksi_json' => $grafikTransaksi->toJson(),
            'chartProdukLabel' => $chartProdukLabel, 
            'chartProdukData'  => $chartProdukData,  
            'produks_list'  => $produks_list,
            'produk_filter' => $produk_filter,
            'tgl_mulai'     => $tgl_mulai,
            'tgl_selesai'   => $tgl_selesai,
        ]);
    }

    // --- PRIVATE HELPER FUNCTIONS ---

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

    private function getTopPelanggan()
    {
        return DB::table('pelanggan')
            ->where('is_member', 1)
            ->orderByDesc('Frekuensi_Pembelian')
            ->limit(5)
            ->get();
    }

    // [BARU] Fungsi untuk Tabel ke-3: Metode Pembayaran
    private function getTopMetodePembayaran($tgl_mulai, $tgl_selesai)
    {
        return DB::table('transaksi')
            ->when($tgl_mulai, fn($q) => $q->whereDate('Tanggal', '>=', $tgl_mulai))
            ->when($tgl_selesai, fn($q) => $q->whereDate('Tanggal', '<=', $tgl_selesai))
            ->select('Metode_Pembayaran', DB::raw('COUNT(ID_Transaksi) as total_usage'))
            ->groupBy('Metode_Pembayaran')
            ->orderByDesc('total_usage')
            ->limit(5)
            ->get();
    }

    private function getGrafikTransaksi($tgl_mulai, $tgl_selesai, $produk_filter)
    {
        return DB::table('transaksi as t')
            ->leftJoin('detail_transaksi as dt', 't.ID_Transaksi', '=', 'dt.ID_Transaksi')
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

    private function getTransaksiTerbaru($tgl_mulai, $tgl_selesai, $produk_filter)
    {
        return DB::table('transaksi as t')
            ->leftJoin('users as u', 't.ID_User', '=', 'u.ID_User')
            ->when($tgl_mulai, fn($q) => $q->whereDate('t.Tanggal', '>=', $tgl_mulai))
            ->when($tgl_selesai, fn($q) => $q->whereDate('t.Tanggal', '<=', $tgl_selesai))
            ->when($produk_filter, function($q) use ($produk_filter) {
                $q->whereExists(function ($sub) use ($produk_filter) {
                    $sub->select(DB::raw(1))
                        ->from('detail_transaksi as dt')
                        ->whereColumn('dt.ID_Transaksi', 't.ID_Transaksi')
                        ->where('dt.ID_Produk', $produk_filter);
                });
            })
            ->select('t.ID_Transaksi', 't.Tanggal', 't.TotalHarga', 'u.Nama_User as Nama_Kasir')
            ->orderBy('t.Tanggal', 'desc')
            ->limit(5)
            ->get();
    }

    public function exportPDF(Request $request)
    {
        $chartImagePath = null;
        $chartProdukPath = null;
        $user = Auth::user();

        $tgl_mulai = $request->tgl_mulai;
        $tgl_selesai = $request->tgl_selesai;
        $produk_filter = $request->produk_id;

        $produkNama = null;
        if ($produk_filter) {
            $produkNama = Produk::where('ID_Produk', $produk_filter)->value('Nama_Produk');
        }

        $statistik = $this->getStatisticData($tgl_mulai, $tgl_selesai, $produk_filter);
        $topProduk = $this->getTopProduk($tgl_mulai, $tgl_selesai);
        $topPelanggan = $this->getTopPelanggan();
        $grafikTransaksi = $this->getGrafikTransaksi($tgl_mulai, $tgl_selesai, $produk_filter);
        $transaksiTerbaru = $this->getTransaksiTerbaru($tgl_mulai, $tgl_selesai, $produk_filter);
        
        // [GANTI] Ambil data Metode Pembayaran untuk PDF
        $topMetode = $this->getTopMetodePembayaran($tgl_mulai, $tgl_selesai);

        if ($request->chart_image) {
            $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $request->chart_image);
            $imageData = base64_decode($base64);
            $filename = 'chart-line-' . time() . '.png';
            $absolutePath = storage_path('app/public/' . $filename);
            file_put_contents($absolutePath, $imageData);
            $chartImagePath = $absolutePath;
        }

        if ($request->chart_produk_image) {
            $base64Pie = preg_replace('#^data:image/\w+;base64,#i', '', $request->chart_produk_image);
            $imagePieData = base64_decode($base64Pie);
            $filenamePie = 'chart-pie-' . time() . '.png';
            $absolutePathPie = storage_path('app/public/' . $filenamePie);
            file_put_contents($absolutePathPie, $imagePieData);
            $chartProdukPath = $absolutePathPie;
        }

        $pdf = Pdf::loadView('exportPdfDashboard', [
            'namaUser'        => $user->Nama_User,
            'roleUser'        => $user->Role,
            'statistik'       => $statistik,
            'topProduk'       => $topProduk,
            'topPelanggan'    => $topPelanggan,
            'topMetode'       => $topMetode, // Kirim ke PDF
            'grafikTransaksi' => $grafikTransaksi,
            'chartImagePath'  => $chartImagePath,
            'chartProdukPath' => $chartProdukPath, 
            'tgl_mulai'       => $tgl_mulai,
            'tgl_selesai'     => $tgl_selesai,
            'produk_filter'   => $produk_filter,
            'produkNama'      => $produkNama,
            'transaksiTerbaru'=> $transaksiTerbaru,
        ]);

        return $pdf->stream('Dashboard-SIDAC.pdf');
    }
}