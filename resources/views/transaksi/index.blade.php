@extends('layouts.app')
@section('title', 'Kelola Transaksi')
@section('content')
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 12px; border-radius: 6px; text-decoration: none; display: inline-block; font-size: 0.9em; font-weight: bold; border: none; cursor: pointer; }
        .btn-detail { background-color: #0d6efd; color: white; font-size: 0.8em; padding: 5px 10px; }
        .btn i { margin-right: 5px; }

        /* === CSS BARU SESUAI GAMBAR === */
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

        .filter-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            border: 1px solid #eee;
        }
        .filter-card h2 {
            margin-top: 0;
            font-size: 1.2em;
        }
        .filter-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr; /* 3 Kolom */
            gap: 20px;
            align-items: flex-end; /* Sejajarkan bagian bawah */
        }
        .filter-group { display: flex; flex-direction: column; }
        .filter-group label {
            font-weight: bold;
            font-size: 0.9em;
            margin-bottom: 5px;
            color: #555;
        }
        .filter-group input, .filter-group select {
            width: 100%;
            padding: 9px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-filter { background-color: #0d6efd; color: white; padding: 10px 15px; }
        
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
        <h2>Filter Transaksi</h2>
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
            
            <div style="margin-top: 15px;">
                <button type="submit" class="btn btn-filter"><i class="fa fa-search"></i> Terapkan Filter</button>
                @if(isset($tgl_mulai) || isset($tgl_selesai) || isset($produk_filter))
                    <a href="{{ route('transaksi.index') }}" style="margin-left: 10px; font-size: 0.9em;">Reset Filter</a>
                @endif
            </div>
        </form>
    </div>
    
    @if (session('success'))
        <div style="color: green; margin-bottom: 15px; background: #e6f7ec; padding: 10px; border-radius: 6px;">
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
                <th>Aksi</th>
            </tr>
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
                <td colspan="7" style="text-align: center;">Tidak ada data transaksi yang cocok dengan filter Anda.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection