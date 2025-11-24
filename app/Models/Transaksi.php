<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    
    // Karena ID Transaksi kita string (TRX0001), bukan Auto Increment Integer
    protected $primaryKey = 'ID_Transaksi';
    public $incrementing = false;
    protected $keyType = 'string';

    // === SOLUSI ERROR UPDATE_AT ===
    // Baris ini mematikan fitur otomatis created_at & updated_at
    public $timestamps = false; 

    // === DAFTAR KOLOM YANG BOLEH DIISI ===
    protected $fillable = [
        'ID_Transaksi',
        'Tanggal',
        'TotalHarga',
        'Metode_Pembayaran',
        'ID_User',
        'ID_Pelanggan', // Pastikan ini ada agar tidak error ID Pelanggan lagi
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'ID_User', 'ID_User');
    }

    // Relasi ke Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'ID_Pelanggan', 'ID_Pelanggan');
    }

    // Relasi ke Detail Transaksi
    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'ID_Transaksi', 'ID_Transaksi');
    }
}