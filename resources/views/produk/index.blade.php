@extends('layouts.app')

{{-- Mengatur Judul Halaman --}}
@section('title', 'Kelola Produk')

{{-- Mengisi Konten Utama --}}
@section('content')
    <style>
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
        <h1>Kelola Data Produk</h1>
        <a href="{{ route('produk.create') }}" class="btn btn-tambah">Tambah Produk Baru</a>
    </div>
    
    @if (session('success'))
        <div style="color: green; margin-top: 10px;">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID Produk</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produks as $produk)
            <tr>
                <td>{{ $produk->ID_Produk }}</td>
                <td>{{ $produk->Nama_Produk }}</td>
                <td>{{ $produk->Kategori }}</td>
                <td>Rp {{ number_format($produk->Harga, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('produk.edit', $produk->ID_Produk) }}" class="btn btn-edit">Edit</a>
                    <form action="{{ route('produk.destroy', $produk->ID_Produk) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection