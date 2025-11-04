@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<style>
    /* --- STYLE HALAMAN PELANGGAN --- */

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        background-color: white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    th {
        background-color: #007bff;
        color: white;
        text-align: center;
        padding: 12px 14px;
        font-weight: 600;
    }

    td {
        padding: 10px 14px;
        border-bottom: 1px solid #eee;
        text-align: center;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background-color: #f4f8ff;
    }

    /* --- TOMBOL STYLE --- */
    .btn {
    display: inline-block;
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    text-align: center;
    border: none;
    cursor: pointer;
    font-size: 0.9em;
    margin: 2px 4px; /* kasih jarak halus antar tombol */
    }

    .btn-tambah {
        background-color: #28a745;
        color: white;
        padding: 10px 18px; /* lebih besar dari tombol lain */
        font-size: 0.95em;
    }

    /* Tombol Edit — sedang */
    .btn-edit {
        background-color: #ffc107;
        color: black;
        padding: 10px 17px;
    }

    /* Tombol Hapus — sedikit lebih kecil dan ringkas */
    .btn-hapus {
        background-color: #dc3545;
        color: white;
        padding: 10px 17px;
    }

    /* Efek hover semua tombol */
    .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
</style>
    
    <div class="header">
        <h1>Kelola Data User</h1>
        <a href="{{ route('user.create') }}" class="btn btn-tambah">Tambah User Baru</a>
    </div>
    
    @if (session('success'))
        <div style="color: green; margin-top: 10px;">
            {{ session('success') }}
        </div>
    @endif

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
            @foreach ($users as $user)
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
            @endforeach
        </tbody>
    </table>
@endsection