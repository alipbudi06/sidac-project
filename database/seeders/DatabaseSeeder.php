<?php

namespace Database\Seeders;

// use App\Models\User; // Kita tidak perlu ini lagi
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // HAPUS KODE BAWAAN LARAVEL
        // // User::factory(10)->create();
        // // User::factory()->create([
        // //     'name' => 'Test User',
        // //     'email' => 'test@example.com',
        // // ]);

        // TAMBAHKAN PANGGILAN INI
        // Ini akan memberitahu Laravel untuk menjalankan file SidacSeeder.php
        // yang berisi semua data palsu kita (Produk, Pelanggan, Transaksi, dll)
        $this->call([
            SidacSeeder::class,
        ]);
    }
}