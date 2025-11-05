<?php

namespace App\Imports;

use App\Models\Transaksi;
use App\Models\Pelanggan; // <-- 1. IMPORT MODEL PELANGGAN
use Illuminate\Support\Collection; // <-- 2. IMPORT COLLECTION
use Maatwebsite\Excel\Concerns\ToCollection; // <-- 3. UBAH DARI ToModel
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB; // <-- 4. IMPORT DB (untuk keamanan)

// 5. Ubah 'ToModel' menjadi 'ToCollection'
class TransaksiImport implements ToCollection, WithHeadingRow 
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        // Gunakan Transaksi Database agar jika 1 baris gagal, semua dibatalkan
        DB::beginTransaction();
        try {
            
            foreach ($rows as $row) 
            {
                // 6. Buat Transaksi (sama seperti sebelumnya)
                Transaksi::create([
                    'ID_Transaksi'      => $row['id_transaksi'],
                    'ID_User'           => $row['id_user'],
                    'ID_Pelanggan'      => $row['id_pelanggan'],
                    'Tanggal'           => \Carbon\Carbon::parse($row['tanggal']),
                    'TotalHarga'        => $row['totalharga'],
                    'Metode_Pembayaran' => $row['metode_pembayaran'],
                ]);

                // ===================================================
                // 7. TAMBAHKAN LOGIKA INCREMENT (INI PERBAIKANNYA)
                // ===================================================
                $pelanggan = Pelanggan::find($row['id_pelanggan']);
                if ($pelanggan) {
                    $pelanggan->increment('Frekuensi_Pembelian');
                }
            }

            DB::commit(); // Simpan semua perubahan jika loop berhasil
        
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error
            // Lempar error kembali ke Controller
            throw $e; 
        }
    }
}