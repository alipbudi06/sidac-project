<?php

namespace App\Imports;

use App\Models\Produk;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Membaca baris header

class ProdukImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        // 1. Ambil ID produk terakhir dari database
        $lastProduk = Produk::orderBy('ID_Produk', 'desc')->first();
        $num = $lastProduk ? (int) substr($lastProduk->ID_Produk, 1) : 0;

        foreach ($rows as $row) 
        {
            // 2. Buat ID baru untuk setiap baris di file
            $num++;
            $newId = 'P' . str_pad($num, 3, '0', STR_PAD_LEFT);

            // 3. Buat produk baru
            // Pastikan header di file Excel Anda adalah 'nama_produk', 'kategori', 'harga'
            Produk::create([
                'ID_Produk'   => $newId,
                'Nama_Produk' => $row['nama_produk'],
                'Kategori'    => $row['kategori'],
                'Harga'       => $row['harga'],
            ]);
        }
    }
}