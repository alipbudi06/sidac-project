<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class UserImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if (!$row['email_user']) continue;

            // ================================
            // 1. CEK APAKAH USER INI SUDAH ADA?
            // ================================
            $existing = User::where('Email_User', $row['email_user'])->first();

            if ($existing) {
                // --- UPDATE DATA USER YANG SUDAH ADA ---
                $existing->update([
                    'Nama_User'   => $row['nama_user'],
                    'Username'    => $row['username'],
                    'Role'        => $row['role'],
                    'Password'    => $row['password']
                                        ? Hash::make($row['password'])
                                        : $existing->Password,
                ]);
                
                continue; // HENTIKAN, JANGAN BUAT USER BARU
            }

            // =======================================================
            // 2. GENERATE ID BARU SESUAI ROLE (M = Manajer, K = Pegawai)
            // =======================================================

            $prefix = ($row['role'] === 'Manajer Operasional') ? 'M' : 'K';

            // Cari kode terakhir DENGAN prefix yang sama
            $lastUserWithPrefix = User::where('ID_User', 'LIKE', $prefix . '%')
                                      ->orderBy('ID_User', 'desc')
                                      ->first();

            $num = $lastUserWithPrefix 
                    ? intval(substr($lastUserWithPrefix->ID_User, 1)) 
                    : 0;

            $newId = $prefix . str_pad($num + 1, 3, '0', STR_PAD_LEFT);

            // ================================
            // 3. BUAT USER BARU
            // ================================
            User::create([
                'ID_User'     => $newId,
                'Nama_User'   => $row['nama_user'],
                'Username'    => $row['username'],
                'Email_User'  => $row['email_user'],
                'Password'    => Hash::make($row['password']),
                'Role'        => $row['role'],
            ]);
        }
    }
}
