<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 

return new class extends Migration
{
    public function up(): void
    {
        // View untuk Top 5 Produk (Tidak berubah)
        DB::statement("
            CREATE VIEW v_top_produk AS
            SELECT
                p.Nama_Produk,
                SUM(dt.Jumlah_Produk) as total_terjual
            FROM produk p
            JOIN detail_transaksi dt ON p.ID_Produk = dt.ID_Produk
            JOIN transaksi t ON dt.ID_Transaksi = t.ID_Transaksi
            GROUP BY p.ID_Produk, p.Nama_Produk, t.Tanggal
            ORDER BY total_terjual DESC LIMIT 5;
        ");

        // PERBAIKAN: View untuk Top 5 Member Loyal
        DB::statement("
            CREATE VIEW v_top_pelanggan AS
            SELECT
                pl.ID_Pelanggan,
                pl.Nama_Pelanggan,
                COUNT(t.ID_Transaksi) AS Frekuensi_Pembelian,
                t.Tanggal
            FROM pelanggan pl
            JOIN transaksi t ON pl.ID_Pelanggan = t.ID_Pelanggan
            WHERE pl.is_member = 1
            GROUP BY pl.ID_Pelanggan, pl.Nama_Pelanggan, t.Tanggal
            ORDER BY total_terjual DESC LIMIT 5;
        ");

        // View untuk Grafik Pendapatan (Tidak berubah)
        DB::statement("
            CREATE VIEW v_pendapatan_bulanan AS
            SELECT
                strftime('%Y-%m', Tanggal) as bulan,
                SUM(TotalHarga) as total
            FROM transaksi
            GROUP BY bulan;
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_top_produk');
        DB::statement('DROP VIEW IF EXISTS v_top_pelanggan');
        DB::statement('DROP VIEW IF EXISTS v_pendapatan_bulanan');
    }
};