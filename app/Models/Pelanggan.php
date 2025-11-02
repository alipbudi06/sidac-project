<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     */
    protected $table = 'pelanggan';

    /**
     * Primary Key kustom.
     */
    protected $primaryKey = 'ID_Pelanggan';

    /**
     * Primary Key bukan auto-increment.
     */
    public $incrementing = false;

    /**
     * Tipe data Primary Key adalah string.
     */
    protected $keyType = 'string';

    /**
     * Model ini tidak menggunakan timestamps (created_at, updated_at).
     */
    public $timestamps = false;
    
    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'ID_Pelanggan',
        'Nama_Pelanggan',
        'Email_Pelanggan',
        'Kata_Sandi',
        'Frekuensi_Pembelian',
    ];

    /**
     * Mendefinisikan relasi 'one-to-many':
     * Satu Pelanggan dapat melakukan BANYAK Transaksi.
     */
    public function transaksi()
    {
        // Relasi: Pelanggan 'hasMany' Transaksi
        // Foreign key di tabel 'transaksi' adalah 'ID_Pelanggan'
        // Primary key di tabel 'pelanggan' ini adalah 'ID_Pelanggan'
        return $this->hasMany(Transaksi::class, 'ID_Pelanggan', 'ID_Pelanggan');
    }
}