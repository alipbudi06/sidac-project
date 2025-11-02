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
        Schema::create('produk', function (Blueprint $table) {
            $table->string('ID_Produk', 8)->primary();
            $table->string('Nama_Produk', 30);
            // ERD Anda FLOAT(10). Kita gunakan decimal(10, 0)
            // '0' berarti tidak ada angka di belakang koma.
            $table->decimal('Harga', 10, 0); 
            $table->string('Kategori', 20);
            // $table->timestamps(); // ERD Anda tidak memilikinya
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};