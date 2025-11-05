@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
<style>
    body {
        background-color: #f5f8fb; /* biar tabelnya kontras */
    }

    h1 {
        font-size: 1.5em;
        margin-bottom: 10px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

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

    /* Tombol Tambah — lebih besar dan menonjol */
    .btn-tambah {
        background-color: #28a745;
        color: white;
        padding: 10px 18px; /* lebih besar dari tombol lain */
        font-size: 0.95em;
    }

    /* Tombol Edit — sedang, biar teksnya pas */
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


    /* ====== Tabel Card Style ====== */
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
</style>

<div class="header">
    <h1>Kelola Data Produk</h1>
    @if (Auth::check() && Auth::user()->Role === 'Manajer Operasional')
        <a href="{{ route('produk.create') }}" class="btn btn-tambah">Tambah Produk Baru</a>
    @endif
</div>

@if (session('success'))
    <div style="color: green; margin-bottom: 10px;">
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
                    @if (Auth::check() && Auth::user()->Role === 'Manajer Operasional')
                        <a href="{{ route('produk.edit', $produk->ID_Produk) }}" class="btn btn-edit">Edit</a>
                        <form action="{{ route('produk.destroy', $produk->ID_Produk) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
