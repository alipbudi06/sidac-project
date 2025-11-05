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
        // 3. Pastikan header di file Excel Anda SAMA PERSIS
        // (cth: 'id_transaksi', 'id_user', 'tanggal', dll.)
        
        return new Transaksi([
            'ID_Transaksi'      => $row['id_transaksi'],
            'ID_User'           => $row['id_user'],
            'ID_Pelanggan'      => $row['id_pelanggan'],
            'Tanggal'           => \Carbon\Carbon::parse($row['tanggal']),
            'TotalHarga'        => $row['totalharga'],
            'Metode_Pembayaran' => $row['metode_pembayaran'],
        ]);
    }
}