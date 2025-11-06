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

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .page-header h1 {
            font-size: 2em; /* ukuran lebih besar */
            margin: 0;
        }
        .btn-back {
            padding: 8px 15px;
            background-color: #3490dc;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .btn-back:hover {
            background-color: #2779bd;
        }
    </style>
    
    <div class="page-header">
        <h1>Detail Transaksi: {{ $transaksi->ID_Transaksi }}</h1>
        <a href="{{ route('transaksi.index') }}" class="btn-back">&larr; Kembali</a>
    </div>

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
                <th>ID Produk</th>
                <th>Nama Produk</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Diskon</th>
                <th class="text-right">Service Charge</th>
                <th class="text-right">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksi->detailTransaksi as $detail)
            <tr>
                <td>{{ $detail->ID_Produk }}</td>
                <td>{{ $detail->produk ? $detail->produk->Nama_Produk : 'Produk Dihapus' }}</td>
                <td class="text-right">{{ $detail->Jumlah_Produk }}</td>
                <td class="text-right">Rp {{ number_format($detail->Diskon ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($detail->Service_Charge ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($detail->SubTotal ?? 0, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada detail produk for transaksi ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">Total Keseluruhan</td>
                <td class="text-right">Rp {{ number_format($transaksi->TotalHarga ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
@endsection