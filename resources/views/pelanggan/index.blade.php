@extends('layouts.app')
@section('title', 'Kelola Pelanggan')
@section('content')
    <style>
        /* ... CSS Anda ... */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 12px; border-radius: 6px; text-decoration: none; display: inline-block; font-size: 0.9em; font-weight: bold; border: none; cursor: pointer; }
        .btn-tambah { background-color: #fd7e14; color: white; }
        .btn-import { background-color: #198754; color: white; }
        .btn-edit { background-color: #ffc107; color: black; font-size: 0.8em; padding: 5px 10px; }
        .btn-hapus { background-color: #dc3545; color: white; font-size: 0.8em; padding: 5px 10px; }
        .header-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .header-controls h1 { font-size: 1.8em; margin: 0; flex: 1; }
        .search-bar { flex: 2; margin-right: 15px; }
        .search-bar input { width: 100%; padding: 9px 15px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; }
        .button-group { display: flex; gap: 10px; }
        .button-group .btn i { margin-right: 5px; }
        .filter-form { margin-top: 20px; background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #eee; }
        .filter-form input[type="text"] { width: 300px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-filter { background-color: #0d6efd; color: white; border: none; padding: 8px 12px; cursor: pointer; border-radius: 4px; }
        .search-wrapper {
            position: relative;
            width: 100%;
        }
        .search-wrapper input {
            width: 100%;
            padding: 10px 38px 10px 35px;
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
            font-size: 16px;
            text-decoration: none;
            font-weight: bold;
        }
        .clear-btn:hover {
            color: #000;
        }
    </style>
    
    <div class="header-controls">
        <h1>Data Pelanggan</h1>
        <!-- Filter -->
        <form method="GET" action="{{ route('pelanggan.index') }}" class="search-bar">
            <div class="search-wrapper">
                <i class="fa fa-search search-icon"></i>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari ID, nama, atau email pelanggan..." 
                    value="{{ request('search') }}"
                >
                @if (request()->has('search') && request('search') !== '')
                    <a href="{{ route('pelanggan.index') }}" class="clear-btn">&times;</a>
                @endif
            </div>
        </form>
        <div class="button-group">
            <a href="{{ route('pelanggan.import.form') }}" class="btn btn-import">
                <i class="fa fa-file-import"></i> Import File
            </a>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Email</th>
                <!-- NAMA KOLOM SESUAI ERD -->
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
                
                <!-- MEMBACA LANGSUNG DARI DATABASE -->
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
                <td colspan="5" style="text-align: center;">Tidak ada data.</td>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $pelanggans->links() }}
    </div>
@endsection