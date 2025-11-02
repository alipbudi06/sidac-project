<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // View untuk Top 5 Produk Terlaris
        DB::statement("
            CREATE VIEW v_top_produk AS
            SELECT
                p.ID_Produk, -- <-- TAMBAHAN
                p.Nama_Produk,
                SUM(dt.Jumlah_Produk) as total_terjual
            FROM produk as p
            JOIN detail_transaksi as dt ON p.ID_Produk = dt.ID_Produk
            GROUP BY p.ID_Produk, p.Nama_Produk; -- <-- TAMBAHAN
        ");

        // View untuk Top 5 Member Loyal
        DB::statement("
            CREATE VIEW v_top_pelanggan AS
            SELECT
                p.ID_Pelanggan, -- <-- TAMBAHAN
                p.Nama_Pelanggan,
                COUNT(t.ID_Transaksi) as total_transaksi
            FROM pelanggan as p
            JOIN transaksi as t ON p.ID_Pelanggan = t.ID_Pelanggan
            GROUP BY p.ID_Pelanggan, p.Nama_Pelanggan; -- <-- TAMBAHAN
        ");

        // View untuk Grafik Pendapatan Bulanan
        DB::statement("
            CREATE VIEW v_pendapatan_bulanan AS
            SELECT
                strftime('%Y-%m', Tanggal) as bulan,
                SUM(TotalHarga) as total
            FROM transaksi
            GROUP BY bulan;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_top_produk');
        DB::statement('DROP VIEW IF EXISTS v_top_pelanggan');
        DB::statement('DROP VIEW IF EXISTS v_pendapatan_bulanan');
    }
};