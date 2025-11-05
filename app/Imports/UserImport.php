<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash; // <-- Import Hash

class UserImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        // Ambil ID user terakhir
        $lastUser = User::orderBy('ID_User', 'desc')->first();
        $num = $lastUser ? (int) substr($lastUser->ID_User, 1) : 0;

        foreach ($rows as $row) 
        {
            $num++;
            // Tentukan awalan (Prefix) berdasarkan Role
            $prefix = ($row['role'] == 'Manajer Operasional') ? 'M' : 'P';
            $newId = $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);

            // Buat user baru
            // Header file: nama_user, username, email_user, password, role, nomor_hp (opsional)
            User::create([
                'ID_User'     => $newId,
                'Nama_User'   => $row['nama_user'],
                'Username'    => $row['username'],
                'Email_User'  => $row['email_user'],
                'Password'    => Hash::make($row['password']), // <-- Hashing password
                'Role'        => $row['role'],
                'Nomor_HP'    => $row['nomor_hp'] ?? null,
            ]);
        }
    }
}