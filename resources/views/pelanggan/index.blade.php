@extends('layouts.app')
@section('title', 'Kelola Pelanggan')
@section('content')
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 12px; border-radius: 6px; text-decoration: none; display: inline-block; font-size: 0.9em; font-weight: bold; border: none; cursor: pointer; }
        .btn-tambah { background-color: #fd7e14; color: white; } /* Oranye */
        .btn-tambah:hover {background-color: #e8590c; color: white}
        .btn-import { background-color: #198754; color: white; } /* Hijau */
        .btn-import:hover {background-color: #157347; color: white}
        .btn-edit { background-color: #ffc107; color: black; font-size: 0.8em; padding: 5px 10px; }
        .btn-edit:hover {background-color: #e0a800; color: black}
        .btn-hapus { background-color: #dc3545; color: white; font-size: 0.8em; padding: 5px 10px; }
        .btn-hapus:hover {background-color: #c82333; color: white}
        
        /* === CSS BARU UNTUK HEADER/FILTER (SESUAI GAMBAR) === */
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
        .search-wrapper {
            position: relative;
            width: 100%;
        }
        .search-wrapper input {
            width: 100%;
            padding: 10px 38px 10px 35px; /* kanan buat X, kiri buat icon */
            border: 1px solid #ccc;
            border-radius: 10px;
            box-sizing: border-box;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .search-wrapper input:focus {
            border-color: #0d6efd;
            outline: none;
            box-shadow: 0 0 5px rgba(13, 110, 253, 0.2);
        }
        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 14px;
        }
        .clear-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: color 0.2s;
        }
        .clear-btn:hover {
            color: #000;
        }
    </style>
    
    <div class="header-controls">
        <h1>Data Pelanggan</h1>
        
        <form method="GET" action="{{ route('pelanggan.index') }}" class="search-bar">
            <div class="search-wrapper">
                <i class="fa fa-search search-icon"></i>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari ID, nama, atau email pelanggan..." 
                    value="{{ request('search') }}"
                    id="searchInput"
                >
                @if (request()->has('search') && request('search') !== '')
                    <a href="{{ route('pelanggan.index') }}" class="clear-btn">&times;</a>
                @endif
            </div>
        </form>

        @if (Auth::check() && Auth::user()->Role === 'Manajer Operasional')
        <div class="button-group">
            <a href="{{ route('pelanggan.import.form') }}" class="btn btn-import">
                <i class="fa fa-file-import"></i> Import File
            </a>
            <a href="{{ route('pelanggan.create') }}" class="btn btn-tambah">
                + Tambah
            </a>
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Email</th>
                <th>Frekuensi Pembelian</th>
                @if (Auth::check() && Auth::user()->Role === 'Manajer Operasional')
                    <th>Aksi</th>
                @endif
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
                @if (Auth::check() && Auth::user()->Role === 'Manajer Operasional')
                <td>
                    <a href="{{ route('pelanggan.edit', $pelanggan->ID_Pelanggan) }}" class="btn btn-edit">Edit</a>
                    <form action="{{ route('pelanggan.destroy', $pelanggan->ID_Pelanggan) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                    </form>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ Auth::check() && Auth::user()->Role === 'Manajer Operasional' ? 5 : 4 }}" style="text-align: center;">
                    Tidak ada data member yang cocok dengan pencarian Anda.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $pelanggans->links('pagination::bootstrap-5') }}
    </div>
@endsection