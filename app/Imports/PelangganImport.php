<?php

namespace App\Imports;

use App\Models\Pelanggan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Membaca baris header

class PelangganImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        // 1. Ambil ID pelanggan terakhir dari database
        $lastPelanggan = Pelanggan::orderBy('ID_Pelanggan', 'desc')->first();
        $num = $lastPelanggan ? (int) substr($lastPelanggan->ID_Pelanggan, 1) : 0;

        foreach ($rows as $row) 
        {
            // 2. Buat ID baru untuk setiap baris di file
            $num++;
            $newId = 'C' . str_pad($num, 3, '0', STR_PAD_LEFT);

            // 3. Buat pelanggan baru
            // Pastikan header di file Excel Anda adalah 'nama_pelanggan', 'email_pelanggan', 'kata_sandi'
            Pelanggan::create([
                'ID_Pelanggan'      => $newId,
                'Nama_Pelanggan'  => $row['nama_pelanggan'],
                'Email_Pelanggan' => $row['email_pelanggan'] ?? null,
                'Kata_Sandi'      => $row['kata_sandi'] ?? '123', // Default '123' jika kosong
                'is_member'         => true, // Semua yang di-import adalah Member
                'Frekuensi_Pembelian' => 0,
            ]);
        }
    }
}