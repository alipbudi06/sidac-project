<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;    
use App\Models\User;                    

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manajer = User::create([
            'ID_User' => 'M001',
            'Role' => 'Manajer Operasional',
            'Nama_User' => 'Admin Manajer',
            'Username' => 'manajer',
            'Email_User' => 'manajer@gmail.com',
            'Password' => Hash::make('12345'),
        ]);
    }
}
