@extends('layouts.app')
@section('title', 'Kelola Transaksi')
@section('content')
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 12px; border-radius: 6px; text-decoration: none; display: inline-block; font-size: 0.9em; font-weight: bold; border: none; cursor: pointer; }
        .btn-detail { background-color: #0d6efd; color: white; font-size: 0.8em; padding: 5px 10px; }
        .btn-detail:hover {background-color: #0a58ca !important; color: white !important;}
        .btn i { margin-right: 5px; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header-controls h1 {
            font-size: 1.8em;
            margin: 0;
        }
        .btn-import { background-color: #198754; color: white; } /* Tombol Import Hijau */
        .btn-import:hover { background-color: #146c43 !important; color: white !important; }

        .btn-filter {
        background-color: #0d6efd;
        color: white;
        font-weight: 600;
        transition: background 0.3s;
    }
    .btn-filter:hover { background-color: #0a58ca !important;
    color: white !important; }

    /* === Kartu Filter === */
    .filter-card {
        background: #fff;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
        border: 1px solid #eee;
    }

    .filter-card h2 {
        margin-top: 0;
        font-size: 1.2em;
        font-weight: 600;
        color: #333;
        margin-bottom: 18px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-weight: 600;
        font-size: 0.9em;
        margin-bottom: 6px;
        color: #444;
    }

    .filter-group input,
    .filter-group select {
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 0.9em;
        box-sizing: border-box;
        transition: border 0.2s;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        border-color: #0d6efd;
        outline: none;
    }

    .filter-actions {
        margin-top: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .btn-reset {
        background-color: #6c757d; /* abu bootstrap */
        color: white;
        font-weight: 600;
        transition: background 0.3s;
    }

    .btn-reset:hover {
        background-color: #5c636a !important;
        color: white !important;
    }

        
    </style>
    
    <div class="header-controls">
        <h1>Data Transaksi</h1>
        <div>
            <a href="{{ route('transaksi.import.form') }}" class="btn btn-import">
                <i class="fa fa-file-import"></i> Import File
            </a>
            </div>
    </div>

    <div class="filter-card">
        <h2><i class="fa fa-filter"></i> Filter Transaksi</h2>
        <form method="GET" action="{{ route('transaksi.index') }}">
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="tgl_mulai"><i class="fa fa-calendar-alt"></i> Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" id="tgl_mulai" value="{{ $tgl_mulai ?? '' }}">
                </div>
                <div class="filter-group">
                    <label for="tgl_selesai"><i class="fa fa-calendar-alt"></i> Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" id="tgl_selesai" value="{{ $tgl_selesai ?? '' }}">
                </div>
                <div class="filter-group">
                    <label for="produk_id"><i class="fa fa-box"></i> Produk</label>
                    <select name="produk_id" id="produk_id">
                        <option value="">Semua Produk</option>
                        @foreach($produks_list as $produk)
                            <option value="{{ $produk->ID_Produk }}" 
                                {{ ($produk_filter ?? '') == $produk->ID_Produk ? 'selected' : '' }}>
                                {{ $produk->Nama_Produk }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-filter">
                    <i class="fa fa-search"></i> Terapkan Filter
                </button>
                @if(isset($tgl_mulai) || isset($tgl_selesai) || isset($produk_filter))
                    <a href="{{ route('transaksi.index') }}" class="btn btn-reset">
                        <i class="fa fa-rotate-left"></i> Reset Filter
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- BAGIAN NOTIFIKASI DI SINI SUDAH SAYA HAPUS --}}
    {{-- Karena sudah ditangani oleh Layout Utama (app.blade.php) --}}

    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
                <th>Kasir (User)</th>
                <th>Total Harga</th>
                <th>Metode</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $trx)
            <tr>
                <td>{{ $trx->ID_Transaksi }}</td>
                <td>{{ \Carbon\Carbon::parse($trx->Tanggal)->format('d M Y') }}</td>
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
                <td colspan="7" style="text-align: center;">Tidak ada data transaksi yang cocok dengan filter Anda.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $transaksis->links('pagination::bootstrap-5') }}
    </div>
@endsection