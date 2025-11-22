<?php

namespace App\Imports;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class TransaksiImport implements ToCollection, WithHeadingRow
{
    protected $counter = null;

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function collection(Collection $rows)
    {
        $lastDetail = DetailTransaksi::selectRaw("MAX(CAST(SUBSTR(ID_DetailTransaksi, 2) AS INTEGER)) as max_num")
            ->value('max_num');
        $this->counter = $lastDetail ? intval($lastDetail) : 0;

        Log::info("=== Mulai Import Transaksi ===");

        Log::info("Last ID_DetailTransaksi", [
            'last_detail' => $lastDetail,
        ]);

        foreach ($rows as $index => $row) {

            try {
                Log::info("Proses baris", [
                    'index' => $index,
                    'row_data' => $row->toArray()
                ]);

                if (!$row['id_transaksi']) {
                    Log::error("ID_Transaksi kosong", [
                        'index' => $index,
                        'row' => $row->toArray()
                    ]);
                    continue;
                }

                $transaksi = Transaksi::firstOrCreate(
                    ['ID_Transaksi' => $row['id_transaksi']],
                    [
                        'Tanggal'           => Carbon::parse($row['tanggal']),
                        'TotalHarga'        => $row['totalharga'],
                        'Metode_Pembayaran' => $row['metode_pembayaran'],
                        'ID_User'           => $row['id_user'],
                    ]
                );

                Log::info("Transaksi Created / Found", [
                    'ID_Transaksi' => $transaksi->ID_Transaksi
                ]);

                $this->counter++;
                $newDetailId = 'D' . str_pad($this->counter, 4, '0', STR_PAD_LEFT);

                Log::info("Generated Detail ID", [
                    'ID_DetailTransaksi' => $newDetailId
                ]);

                DetailTransaksi::create([
                    'ID_DetailTransaksi' => $newDetailId,
                    'ID_Transaksi'       => $transaksi->ID_Transaksi,
                    'ID_Produk'          => $row['id_produk'],
                    'Jumlah_Produk'      => $row['jumlah_produk'],
                    'Diskon'             => $row['diskon'] ?? 0,
                    'Service_Charge'     => $row['service_charge'] ?? 0,
                    'SubTotal'           => $row['subtotal'],
                ]);

                Log::info("Detail Created", [
                    'ID_DetailTransaksi' => $newDetailId
                ]);
            } catch (\Exception $e) {

                Log::error("ERROR di baris", [
                    'index' => $index,
                    'row' => $row->toArray(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                throw $e;
            }
        }

        Log::info("=== Import selesai tanpa error ===");
    }
}
