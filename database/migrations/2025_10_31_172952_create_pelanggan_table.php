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
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->integer('ID_Pelanggan')->primary();
            $table->string('Nama_Pelanggan', 20);
            $table->string('Email_Pelanggan', 100)->nullable();
            $table->integer('Frekuensi_Pembelian')->default(0);
            // $table->timestamps(); // ERD Anda tidak memilikinya
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};