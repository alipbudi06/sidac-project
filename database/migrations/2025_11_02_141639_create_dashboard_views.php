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
                p.ID_Produk,
                p.Nama_Produk,
                SUM(dt.Jumlah_Produk) as total_terjual
            FROM produk as p
            JOIN detail_transaksi as dt ON p.ID_Produk = dt.ID_Produk
            GROUP BY p.ID_Produk, p.Nama_Produk;
        ");

        // PERBAIKAN: View untuk Top 5 Member Loyal
        DB::statement("
            CREATE VIEW v_top_pelanggan AS
            SELECT
                ID_Pelanggan,
                Nama_Pelanggan,
                Frekuensi_Pembelian 
            FROM pelanggan
            WHERE is_member = 1;
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