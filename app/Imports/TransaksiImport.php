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

    // Pastikan delimiter sesuai dengan CSV (Titik Koma)
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function collection(Collection $rows)
    {
        // 1. Ambil nomor urut terakhir dari DetailTransaksi agar ID tidak bentrok
        $lastDetail = DetailTransaksi::selectRaw("MAX(CAST(SUBSTR(ID_DetailTransaksi, 2) AS INTEGER)) as max_num")
            ->value('max_num');
        $this->counter = $lastDetail ? intval($lastDetail) : 0;

        Log::info("=== Mulai Import Transaksi ===");

        foreach ($rows as $index => $row) {
            try {
                // Lewati jika baris kosong (tidak ada ID Transaksi)
                if (!$row['id_transaksi']) {
                    continue;
                }

                // === LOGIKA PERBAIKAN TANGGAL ===
                $tanggalRaw = $row['tanggal'];
                $tanggalFix = null;

                // Cek apakah tanggal menggunakan garis miring (contoh: 20/11/2025)
                if (str_contains($tanggalRaw, '/')) {
                    try {
                        // Paksa baca format Indonesia (Hari/Bulan/Tahun)
                        $tanggalFix = Carbon::createFromFormat('d/m/Y', $tanggalRaw);
                    } catch (\Exception $e) {
                        // Jika gagal, coba parsing standar
                        $tanggalFix = Carbon::parse($tanggalRaw);
                    }
                } else {
                    // Jika formatnya sudah standar (2025-11-20), langsung parse
                    $tanggalFix = Carbon::parse($tanggalRaw);
                }
                // ================================

                // 2. Simpan atau Cari Data Transaksi Utama
                $transaksi = Transaksi::firstOrCreate(
                    ['ID_Transaksi' => $row['id_transaksi']],
                    [
                        'Tanggal'           => $tanggalFix, // Pakai tanggal yang sudah diperbaiki
                        'TotalHarga'        => $row['totalharga'],
                        'Metode_Pembayaran' => $row['metode_pembayaran'],
                        'ID_User'           => $row['id_user'],
                    ]
                );

                // 3. Generate ID Detail Transaksi Baru
                $this->counter++;
                $newDetailId = 'D' . str_pad($this->counter, 4, '0', STR_PAD_LEFT);

                // 4. Simpan Detail Produknya
                DetailTransaksi::updateOrCreate([
                    'ID_DetailTransaksi' => $newDetailId,
                    'ID_Transaksi'       => $transaksi->ID_Transaksi,
                    'ID_Produk'          => $row['id_produk'],
                    'Jumlah_Produk'      => $row['jumlah_produk'],
                    'Diskon'             => $row['diskon'] ?? 0,
                    'Service_Charge'     => $row['service_charge'] ?? 0,
                    'SubTotal'           => $row['subtotal'],
                    ]
                );


            } catch (\Exception $e) {
                // Catat error ke log storage/logs/laravel.log jika gagal
                Log::error("ERROR Import Baris $index: " . $e->getMessage());
                throw $e;
            }
        }
    }
}