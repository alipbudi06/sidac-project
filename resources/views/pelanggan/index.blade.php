@extends('layouts.app')

@section('title', 'Kelola Pelanggan')

@section('content')
    <style>
        /* CSS ini spesifik hanya untuk halaman ini */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 5px 10px; border-radius: 4px; text-decoration: none; display: inline-block; }
        .btn-tambah { background-color: #28a745; color: white; }
        .btn-edit { background-color: #ffc107; color: black; }
        .btn-hapus { background-color: #dc3545; color: white; border: none; cursor: pointer; }
        .header { display: flex; justify-content: space-between; align-items: center; }
    </style>
    
    <div class="header">
        <h1>Kelola Data Pelanggan</h1>
        <a href="{{ route('pelanggan.create') }}" class="btn btn-tambah">Tambah Pelanggan Baru</a>
    </div>
    
    @if (session('success'))
        <div style="color: green; margin-top: 10px;">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Email</th>
                <th>Frekuensi Beli</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pelanggans as $pelanggan)
            <tr>
                <td>{{ $pelanggan->ID_Pelanggan }}</td>
                <td>{{ $pelanggan->Nama_Pelanggan }}</td>
                <td>{{ $pelanggan->Email_Pelanggan }}</td>
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
            @endforeach
        </tbody>
    </table>
@endsection