<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;   
use Illuminate\Support\Facades\Hash; 
use App\Models\User;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;

class SidacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kosongkan tabel
        // Nonaktifkan foreign key check untuk SQLite agar truncate berhasil
        DB::statement('PRAGMA foreign_keys = OFF;');
        DetailTransaksi::truncate();
        Transaksi::truncate();
        Produk::truncate();
        Pelanggan::truncate();
        User::truncate();
        DB::statement('PRAGMA foreign_keys = ON;');

        // 2. Buat User
        $manajer = User::create([ 'ID_User' => 'M001', 'Role' => 'Manajer Operasional', 'Nama_User' => 'Admin Manajer', 'Username' => 'manajer', 'Email_User' => 'manajer@sidac.com', 'Password' => Hash::make('12345') ]);
        $pegawai = User::create([ 'ID_User' => 'K001', 'Role' => 'Pegawai', 'Nama_User' => 'Kasir Pegawai', 'Username' => 'pegawai', 'Email_User' => 'pegawai@sidac.com', 'Password' => Hash::make('12345') ]);
        $users = [$manajer, $pegawai];

        // 3. Buat Produk
        $p1 = Produk::create(['ID_Produk' => 'F001', 'Nama_Produk' => 'Americano', 'Kategori' => 'Kopi', 'Harga' => 22000]);
        $p2 = Produk::create(['ID_Produk' => 'F002', 'Nama_Produk' => 'Matcha Latte', 'Kategori' => 'Non-Kopi', 'Harga' => 28000]);
        $p3 = Produk::create(['ID_Produk' => 'F003', 'Nama_Produk' => 'Croissant', 'Kategori' => 'Pastry', 'Harga' => 18000]);
        $p4 = Produk::create(['ID_Produk' => 'F004', 'Nama_Produk' => 'Caffe Latte', 'Kategori' => 'Kopi', 'Harga' => 25000]);
        $p5 = Produk::create(['ID_Produk' => 'F005', 'Nama_Produk' => 'Red Velvet', 'Kategori' => 'Non-Kopi', 'Harga' => 27000]);
        $produks = [$p1, $p2, $p3, $p4, $p5];

        // 4. Buat Pelanggan (Member)
        // Catatan: Kita set Frekuensi_Pembelian ke 0 dulu, nanti dihitung ulang di akhir
        $c1 = Pelanggan::create(['ID_Pelanggan' => '1', 'Nama_Pelanggan' => 'Budi Santoso', 'Email_Pelanggan' => 'budi@mail.com', 'is_member' => true, 'Frekuensi_Pembelian' => 0]);
        $c2 = Pelanggan::create(['ID_Pelanggan' => '2', 'Nama_Pelanggan' => 'Citra Lestari', 'Email_Pelanggan' => 'citra@mail.com', 'is_member' => true, 'Frekuensi_Pembelian' => 0]);
        $c3 = Pelanggan::create(['ID_Pelanggan' => '3', 'Nama_Pelanggan' => 'David Kim', 'Email_Pelanggan' => 'david@mail.com', 'is_member' => true, 'Frekuensi_Pembelian' => 0]);
        $c4 = Pelanggan::create(['ID_Pelanggan' => '4', 'Nama_Pelanggan' => 'Elisa Putri', 'Email_Pelanggan' => 'elisa@mail.com', 'is_member' => true, 'Frekuensi_Pembelian' => 0]);
        $c5 = Pelanggan::create(['ID_Pelanggan' => '5', 'Nama_Pelanggan' => 'Fajar Nugroho', 'Email_Pelanggan' => 'fajar@mail.com', 'is_member' => true, 'Frekuensi_Pembelian' => 0]);
        $pelanggans = [$c1, $c2, $c3, $c4, $c5];

        // 5. Buat Transaksi & Detail Transaksi (Palsu)
        for ($i = 1; $i <= 50; $i++) {
            $total_transaksi = 0;
            $random_user = $users[array_rand($users)];
            $random_timestamp = time() - rand(0, 90 * 24 * 60 * 60); 

            $transaksi = Transaksi::create([
                'ID_Transaksi' => uniqid('SIDAC'),
                'ID_User' => $random_user->ID_User,
                'Tanggal' => date('Y-m-d H:i:s', $random_timestamp),
                'TotalHarga' => 0, 
                'Metode_Pembayaran' => (rand(0, 1) ? 'QRIS' : 'Cash')
            ]);
            
            $item_count = rand(1, 3);
            for ($j = 0; $j < $item_count; $j++) {
                $random_produk = $produks[array_rand($produks)];
                $jumlah = rand(1, 2);
                $sub_total = $random_produk->Harga * $jumlah;
                $total_transaksi += $sub_total;

                DetailTransaksi::create([
                    'ID_DetailTransaksi' => 'DTL' . str_pad($i, 4, '0', STR_PAD_LEFT) . '-' . $j,
                    'ID_Transaksi' => $transaksi->ID_Transaksi,
                    'ID_Produk' => $random_produk->ID_Produk,
                    'Jumlah_Produk' => $jumlah,
                    'SubTotal' => $sub_total
                ]);
            }

            $transaksi->TotalHarga = $total_transaksi;
            $transaksi->save();
        }

        // ========================================================
        // 6. SINKRONISASI (INI KUNCINYA)
        // Hitung ulang frekuensi pembelian untuk SEMUA pelanggan
        // berdasarkan transaksi yang baru saja dibuat.
        // ========================================================
        $allPelanggans = Pelanggan::all();
        foreach ($allPelanggans as $pelanggan) {
            $count = Transaksi::where('ID_Pelanggan', $pelanggan->ID_Pelanggan)->count();
            $pelanggan->Frekuensi_Pembelian = $count;
            $pelanggan->save();
        }
    }
}