<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            // Tambahkan kolom baru 'is_member'
            // default(true) berarti semua pelanggan yang sudah ada
            // akan otomatis dianggap sebagai 'Member'.
            $table->boolean('is_member')->default(true)->after('Kata_Sandi');
        });
    }

    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropColumn('is_member');
        });
    }
};