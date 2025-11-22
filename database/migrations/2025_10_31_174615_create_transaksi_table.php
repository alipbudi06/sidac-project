<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->string('ID_Transaksi', 8)->primary();
            $table->string('ID_User', 8);
            $table->dateTime('Tanggal');
            $table->decimal('TotalHarga', 20, 0);
            $table->enum('Metode_Pembayaran', ['QRIS', 'Cash']);

            // Relasi Foreign Key
            $table->foreign('ID_User')->references('ID_User')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};