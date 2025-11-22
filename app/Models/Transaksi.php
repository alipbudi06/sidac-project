<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'ID_Transaksi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'ID_Transaksi',
        'Tanggal',
        'TotalHarga',
        'Metode_Pembayaran',
    ];

    /**
     * Mendefinisikan relasi 'many-to-one' (kebalikan):
     * Satu Transaksi DIMILIKI OLEH SATU User.
     */
    public function user()
    {
        // Relasi: Transaksi 'belongsTo' User
        // Foreign key di tabel 'transaksi' ini adalah 'ID_User'
        // Primary key di tabel 'users' adalah 'ID_User'
        return $this->belongsTo(User::class, 'ID_User', 'ID_User');
    }

    /**
     * Mendefinisikan relasi 'many-to-one' (kebalikan):
     * Satu Transaksi DIMILIKI OLEH SATU Pelanggan.
     */
    // public function pelanggan()
    // {
    //     // Relasi: Transaksi 'belongsTo' Pelanggan
    //     // Foreign key di tabel 'transaksi' ini adalah 'ID_Pelanggan'
    //     // Primary key di tabel 'pelanggan' adalah 'ID_Pelanggan'
    //     return $this->belongsTo(Pelanggan::class, 'ID_Pelanggan', 'ID_Pelanggan');
    // }

    /**
     * Mendefinisikan relasi 'one-to-many':
     * Satu Transaksi memiliki BANYAK item DetailTransaksi.
     */
    public function detailTransaksi()
    {
        // Relasi: Transaksi 'hasMany' DetailTransaksi
        // Foreign key di tabel 'detail_transaksi' adalah 'ID_Transaksi'
        // Primary key di tabel 'transaksi' ini adalah 'ID_Transaksi'
        return $this->hasMany(DetailTransaksi::class, 'ID_Transaksi', 'ID_Transaksi');
    }
}