<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // === MODIFIKASI: Menyesuaikan dengan Primary Key ERD Anda ===
    /**
     * Nama Primary Key kustom Anda.
     */
    protected $primaryKey = 'ID_User';

    /**
     * Memberitahu Laravel bahwa Primary Key Anda BUKAN auto-increment.
     */
    public $incrementing = false;

    /**
     * Memberitahu Laravel bahwa Primary Key Anda adalah string.
     */
    protected $keyType = 'string';
    
    // === MODIFIKASI: Menyesuaikan dengan kolom-kolom ERD Anda ===
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ID_User',
        'Role',
        'Nama_User',
        'Username',
        'Email_User',
        'Nomor_HP',
        'Password', // Menggunakan 'Password' (P besar) dari migrasi
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'Password', // Menggunakan 'Password' (P besar) dari migrasi
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime', // Dihapus karena tidak ada di migrasi Anda
            'Password' => 'hashed', // Menggunakan 'Password' (P besar) dari migrasi
        ];
    }

    // === MODIFIKASI: Memberitahu Laravel nama kolom password Anda ===
    /**
     * WAJIB: Memberitahu sistem Auth nama kolom password Anda yang kustom.
     */
    public function getAuthPassword()
    {
        return $this->Password; // Memberitahu Laravel untuk menggunakan 'Password'
    }

    // === MODIFIKASI: Memberitahu Laravel nama kolom username Anda ===
    /**
     * WAJIB: Memberitahu sistem Auth untuk login menggunakan 'Username'.
     * (Berdasarkan file DPPL Anda, pengguna login dengan 'Username', bukan 'Email_User')
     */
    public function username()
    {
        return 'Username';
    }

    // === MODIFIKASI: Memberitahu Laravel nama kolom email Anda ===
    /**
     * WAJIB: Memberitahu sistem Reset Password nama kolom email Anda yang kustom.
     */
    public function getEmailForPasswordReset()
    {
        return $this->Email_User;
    }

    /**
     * Mendefinisikan relasi 'one-to-many':
     * Satu User (Pegawai/Manajer) dapat mengelola BANYAK Transaksi.
     */
    public function transaksi()
    {
        // Relasi: User 'hasMany' Transaksi
        // Foreign key di tabel 'transaksi' adalah 'ID_User'
        // Primary key di tabel 'users' ini adalah 'ID_User'
        return $this->hasMany(Transaksi::class, 'ID_User', 'ID_User');
    }
}