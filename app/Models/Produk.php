<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     */
    protected $table = 'produk';

    /**
     * Primary Key kustom.
     */
    protected $primaryKey = 'ID_Produk';

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
        'ID_Produk',
        'Nama_Produk',
        'Harga',
        'Kategori',
    ];

    /**
     * Mendefinisikan relasi 'one-to-many':
     * Satu Produk dapat muncul di BANYAK DetailTransaksi.
     */
    public function detailTransaksi()
    {
        // Relasi: Produk 'hasMany' DetailTransaksi
        // Foreign key di tabel 'detail_transaksi' adalah 'ID_Produk'
        // Primary key di tabel 'produk' ini adalah 'ID_Produk'
        return $this->hasMany(DetailTransaksi::class, 'ID_Produk', 'ID_Produk');
    }
}