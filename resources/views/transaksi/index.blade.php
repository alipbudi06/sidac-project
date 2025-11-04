@extends('layouts.app')

@section('title', 'Kelola Transaksi')

@section('content')
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 5px 10px; border-radius: 4px; text-decoration: none; display: inline-block; }
        .btn-detail { background-color: #0d6efd; color: white; }
        .header { display: flex; justify-content: space-between; align-items: center; }
    </style>
    
    <div class="header">
        <h1>Data Transaksi</h1>
        </div>
    
    @if (session('success'))
        <div style="color: green; margin-top: 10px;">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Kasir (User)</th>
                <th>Total Harga</th>
                <th>Metode</th>
                <th>Aksi</th> </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $trx)
            <tr>
                <td>{{ $trx->ID_Transaksi }}</td>
                <td>{{ \Carbon\Carbon::parse($trx->Tanggal)->format('d M Y, H:i') }}</td>
                <td>{{ $trx->pelanggan ? $trx->pelanggan->Nama_Pelanggan : 'N/A' }}</td>
                <td>{{ $trx->user ? $trx->user->Nama_User : 'N/A' }}</td>
                <td>Rp {{ number_format($trx->TotalHarga ?? 0, 0, ',', '.') }}</td>
                <td>{{ $trx->Metode_Pembayaran }}</td>
                <td>
                    <a href="{{ route('transaksi.show', $trx->ID_Transaksi) }}" class="btn btn-detail">
                        Lihat Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Belum ada data transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection