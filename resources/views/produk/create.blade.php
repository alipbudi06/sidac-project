@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <style>
        /* CSS ini spesifik untuk form */
        form { background: #fff; padding: 25px; border-radius: 8px; max-width: 600px; margin: auto; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-size: 0.9em; }
    </style>
    
    <h1>Form Tambah Produk Baru</h1>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produk.store') }}" method="POST">
        @csrf
        <div>
            <label for="ID_Produk">ID Produk (Contoh: P001)</label>
            <input type="text" id="ID_Produk" name="ID_Produk" value="{{ old('ID_Produk') }}" required>
        </div>
        <div>
            <label for="Nama_Produk">Nama Produk</label>
            <input type="text" id="Nama_Produk" name="Nama_Produk" value="{{ old('Nama_Produk') }}" required>
        </div>
        <div>
            <label for="Kategori">Kategori</label>
            <input type="text" id="Kategori" name="Kategori" value="{{ old('Kategori') }}" required>
        </div>
        <div>
            <label for="Harga">Harga (Hanya angka, cth: 25000)</label>
            <input type="number" id="Harga" name="Harga" value="{{ old('Harga') }}" required>
        </div>
        <button type="submit">Simpan Produk</button>
        <a href="{{ route('produk.index') }}" style="margin-left: 10px;">Batal</a>
    </form>
@endsection