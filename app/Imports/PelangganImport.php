<?php

namespace App\Imports;

use App\Models\Pelanggan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PelangganImport implements ToCollection, WithHeadingRow
{
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            // Lewati baris jika tidak ada nama
            if (!$row['nama_pelanggan']) continue;

            // Lewati baris kalau tidak ada ID_Pelanggan
            if (!$row['id_pelanggan']) continue;

            // UPDATE jika ID sudah ada, INSERT jika belum
            Pelanggan::updateOrCreate(
                ['ID_Pelanggan' => $row['id_pelanggan']],  // kondisi pencocokan

                [   // data yang akan diisi/diupdate
                    'Nama_Pelanggan'        => $row['nama_pelanggan'],
                    'Email_Pelanggan'       => $row['email_pelanggan'] ?? null,
                    'is_member'             => true,
                    'Frekuensi_Pembelian'   => (int) ($row['frekuensi_pembelian'] ?? 0)
                ]
            );
        }
    }
}
