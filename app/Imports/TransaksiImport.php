<?php

namespace App\Imports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // <-- 1. Import ini

// 2. Tambahkan WithHeadingRow
class TransaksiImport implements ToModel, WithHeadingRow 
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 3. Logika ini akan membaca setiap baris dari file Excel/CSV
        // Pastikan nama header di file Anda SAMA PERSIS (cth: 'id_transaksi')
        
        return new Transaksi([
            'ID_Transaksi'      => $row['id_transaksi'],
            'ID_User'           => $row['id_user'],
            'ID_Pelanggan'      => $row['id_pelanggan'],
            'Tanggal'           => \Carbon\Carbon::parse($row['tanggal']), // Otomatis konversi tanggal
            'TotalHarga'        => $row['totalharga'],
            'Metode_Pembayaran' => $row['metode_pembayaran'],
        ]);
    }
}