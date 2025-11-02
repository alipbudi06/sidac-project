<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->string('ID_DetailTransaksi', 8)->primary();
            $table->string('ID_Transaksi', 8);
            $table->string('ID_Produk', 8);
            $table->float('Diskon')->default(0); // Sesuai ERD: FLOAT(10)
            $table->float('Service_Charge')->default(0); // Sesuai ERD: FLOAT(20)
            $table->integer('Jumlah_Produk');
            $table->decimal('SubTotal', 20, 0); // Sesuai ERD: FLOAT(20)
            // $table->timestamps(); // ERD Anda tidak memilikinya

            // Relasi Foreign Key
            $table->foreign('ID_Transaksi')->references('ID_Transaksi')->on('transaksi')->onDelete('cascade');
            $table->foreign('ID_Produk')->references('ID_Produk')->on('produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};