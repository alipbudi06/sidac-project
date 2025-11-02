<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi';
    protected $primaryKey = 'ID_DetailTransaksi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    /**
     * Atribut yang bisa diisi secara massal.
     */
    protected $fillable = [
        'ID_DetailTransaksi',
        'ID_Transaksi',
        'ID_Produk',
        'Diskon',
        'Service_Charge',
        'Jumlah_Produk',
        'SubTotal',
    ];

    /**
     * Mendefinisikan relasi 'many-to-one' (kebalikan):
     * Satu DetailTransaksi DIMILIKI OLEH SATU Transaksi.
     */
    public function transaksi()
    {
        // Relasi: DetailTransaksi 'belongsTo' Transaksi
        // Foreign key di tabel 'detail_transaksi' ini adalah 'ID_Transaksi'
        // Primary key di tabel 'transaksi' adalah 'ID_Transaksi'
        return $this->belongsTo(Transaksi::class, 'ID_Transaksi', 'ID_Transaksi');
    }

    /**
     * Mendefinisikan relasi 'many-to-one' (kebalikan):
     * Satu DetailTransaksi mencatat SATU Produk.
     */
    public function produk()
    {
        // Relasi: DetailTransaksi 'belongsTo' Produk
        // Foreign key di tabel 'detail_transaksi' ini adalah 'ID_Produk'
        // Primary key di tabel 'produk' adalah 'ID_Produk'
        return $this->belongsTo(Produk::class, 'ID_Produk', 'ID_Produk');
    }
}