@extends('layouts.app')
@section('title', 'Kelola User')
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
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
        }
        .clear-btn:hover {
            color: #555;
        }

    </style>
    
    <div class="header-controls">
        <h1>Data User</h1>
        
        <form method="GET" action="{{ route('user.index') }}" class="search-bar">
            <div class="search-wrapper">
                <i class="fa fa-search search-icon"></i>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari ID, nama, atau username..." 
                    value="{{ request('search') }}"
                    id="searchInput"
                >
                @if (request()->has('search') && request('search') !== '')
                    <a href="{{ route('user.index') }}" class="clear-btn">&times;</a>
                @endif
            </div>
        </form>

        <div class="button-group">
            <a href="{{ route('user.import.form') }}" class="btn btn-import">
                <i class="fa fa-file-import"></i> Import File
            </a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID User</th>
                <th>Nama User</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr>
                <td>{{ $user->ID_User }}</td>
                <td>{{ $user->Nama_User }}</td>
                <td>{{ $user->Username }}</td>
                <td>{{ $user->Email_User }}</td>
                <td>{{ $user->Role }}</td>
                <td>
                    <a href="{{ route('user.edit', $user->ID_User) }}" class="btn btn-edit">Edit</a>
                    <form action="{{ route('user.destroy', $user->ID_User) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">
                    Tidak ada data user yang cocok dengan pencarian Anda.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection