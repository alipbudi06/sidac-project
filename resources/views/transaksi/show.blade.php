@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
    <style>
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .detail-box {
            background: #f8f9fa;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 8px;
        }
        .detail-box h3 { margin-top: 0; }
        .detail-box p { margin: 5px 0; }
        .detail-box strong { min-width: 120px; display: inline-block; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; font-size: 1.1em; }
    </style>
    
    <h1>Detail Transaksi: {{ $transaksi->ID_Transaksi }}</h1>
    <a href="{{ route('transaksi.index') }}">&larr; Kembali ke Daftar Transaksi</a>

    <div class="detail-grid">
        <div class="detail-box">
            <h3>Informasi Transaksi</h3>
            <p><strong>ID Transaksi:</strong> {{ $transaksi->ID_Transaksi }}</p>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->Tanggal)->format('d M Y, H:i') }}</p>
            <p><strong>Metode:</strong> {{ $transaksi->Metode_Pembayaran }}</p>
        </div>
        <div class="detail-box">
            <h3>Informasi Pihak</h3>
            <p><strong>Pelanggan:</strong> {{ $transaksi->pelanggan ? $transaksi->pelanggan->Nama_Pelanggan : 'N/A' }}</auto-size-text>
            <p><strong>Kasir:</strong> {{ $transaksi->user ? $transaksi->user->Nama_User : 'N/A' }}</p>
        </div>
    </div>

    <h3>Produk yang Dibeli</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksi->detailTransaksi as $detail)
            <tr>
                <td>{{ $detail->produk ? $detail->produk->Nama_Produk : 'Produk Dihapus' }}</td>
                <td class="text-right">{{ $detail->Jumlah_Produk }}</td>
                <td class="text-right">Rp {{ number_format($detail->SubTotal ?? 0, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center;">Tidak ada detail produk untuk transaksi ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" class="text-right">Total Keseluruhan</td>
                <td class="text-right">Rp {{ number_format($transaksi->TotalHarga ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
@endsection