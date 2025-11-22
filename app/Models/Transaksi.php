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

    protected $fillable = [
        'ID_Transaksi',
        'Tanggal',
        'TotalHarga',
        'Metode_Pembayaran',
        'ID_User',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_User', 'ID_User');
    }


    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'ID_Transaksi', 'ID_Transaksi');
    }
}