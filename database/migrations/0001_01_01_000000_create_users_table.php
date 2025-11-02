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
        // === MODIFIKASI: Tabel 'users' disesuaikan dengan ERD Anda ===
        Schema::create('users', function (Blueprint $table) {
            // Ini adalah kolom kustom dari ERD Anda
            $table->string('ID_User', 8)->primary();
            $table->enum('Role', ['Manajer Operasional', 'Pegawai']);
            $table->string('Nama_User', 50);
            $table->string('Username', 30)->unique();
            $table->string('Email_User', 100)->unique();
            $table->string('Nomor_HP', 12)->nullable();
            
            // Mengganti 'password' default dengan 'Password' (sesuai ERD)
            // Panjang 255 disarankan untuk hashing, bukan 30
            $table->string('Password', 255); 

            // Ini adalah kolom standar Laravel, bagus untuk dipertahankan
            $table->rememberToken();
            $table->timestamps();
        });

        // === MODIFIKASI: Disesuaikan agar cocok dengan 'Email_User' ===
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            // Mengganti 'email' menjadi 'Email_User' agar cocok dengan tabel 'users'
            $table->string('Email_User')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // === MODIFIKASI: Disesuaikan agar cocok dengan 'ID_User' (string) ===
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            
            // Mengganti 'foreignId' (integer) menjadi 'string' agar 
            // cocok dengan 'ID_User' Anda yang berupa string
            $table->string('user_id', 8)->nullable()->index();
            $table->foreign('user_id')->references('ID_User')->on('users')->onDelete('cascade');

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};