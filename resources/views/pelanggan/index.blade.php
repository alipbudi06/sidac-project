@extends('layouts.app')
@section('title', 'Kelola Pelanggan')
@section('content')
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 12px; border-radius: 6px; text-decoration: none; display: inline-block; font-size: 0.9em; font-weight: bold; border: none; cursor: pointer; }
        .btn-tambah { background-color: #fd7e14; color: white; } /* Oranye */
        .btn-import { background-color: #198754; color: white; } /* Hijau */
        .btn-edit { background-color: #ffc107; color: black; font-size: 0.8em; padding: 5px 10px; }
        .btn-hapus { background-color: #dc3545; color: white; font-size: 0.8em; padding: 5px 10px; }
        
        /* === CSS BARU UNTUK HEADER/FILTER (SESUAI GAMBAR) === */
        .header-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header-controls h1 {
            font-size: 1.8em;
            margin: 0;
            flex: 1;
        }
        .search-bar {
            flex: 2;
            margin-right: 15px;
        }
        .search-bar input {
            width: 100%;
            padding: 9px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box; /* Penting */
        }
        .button-group {
            display: flex;
            gap: 10px;
        }
        .button-group .btn i {
            margin-right: 5px;
        }
    </style>
    
    <div class="header-controls">
        <h1>Data Pelanggan</h1>
        
        <form method="GET" action="{{ route('pelanggan.index') }}" class="search-bar">
            <input type="text" name="search" placeholder="Cari nama pelanggan..." 
                   value="{{ $search ?? '' }}">
            </form>

        <div class="button-group">
            <a href="{{ route('pelanggan.import.form') }}" class="btn btn-import">
                <i class="fa fa-file-import"></i> Import File
            </a>
            <a href="{{ route('pelanggan.create') }}" class="btn btn-tambah">
                + Tambah
            </a>
        </div>
    </div>
    
    @if (session('success'))
        <div style="color: green; margin-bottom: 15px; background: #e6f7ec; padding: 10px; border-radius: 6px;">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Email</th>
                <th>Frekuensi Pembelian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pelanggans as $pelanggan)
            <tr>
                <td>{{ $pelanggan->ID_Pelanggan }}</td>
                <td>{{ $pelanggan->Nama_Pelanggan }}</td>
                <td>{{ $pelanggan->Email_Pelanggan }}</td>
                <!-- <td>{{ $pelanggan->transaksi_count }}</td> -->
                <td>{{ $pelanggan->Frekuensi_Pembelian }}</td>
                <td>
                    <a href="{{ route('pelanggan.edit', $pelanggan->ID_Pelanggan) }}" class="btn btn-edit">Edit</a>
                    <form action="{{ route('pelanggan.destroy', $pelanggan->ID_Pelanggan) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">
                    Tidak ada data member yang cocok dengan pencarian Anda.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection