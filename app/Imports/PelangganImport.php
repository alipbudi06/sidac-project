<?php

namespace App\Imports;

use App\Models\Pelanggan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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
        foreach ($rows as $row) {
            if (!$row['nama_pelanggan']) continue;
            if (!$row['id_pelanggan']) continue;

            $importFreq = (int) ($row['frekuensi_pembelian'] ?? 0);

            $existing = Pelanggan::where('ID_Pelanggan', $row['id_pelanggan'])->first();

            $newFreq = $existing
                ? (int) $existing->Frekuensi_Pembelian + $importFreq
                : $importFreq;

            Pelanggan::updateOrCreate(
                ['ID_Pelanggan' => $row['id_pelanggan']],
                [
                    'Nama_Pelanggan'        => $row['nama_pelanggan'],
                    'Email_Pelanggan'       => $row['email_pelanggan'] ?? null,
                    'is_member'             => true,
                    'Frekuensi_Pembelian'   => (int) ($row['frekuensi_pembelian'] ?? 0)
                ]
            );
        }
    }
}
