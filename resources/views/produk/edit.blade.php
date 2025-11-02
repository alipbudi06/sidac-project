@extends('layouts.app')

@section('title', 'Edit Produk')

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
    
    <h1>Form Edit Produk</h1>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produk.update', $produk->ID_Produk) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="ID_Produk">ID Produk</label>
            <input type="text" id="ID_Produk" name="ID_Produk" value="{{ $produk->ID_Produk }}" readonly>
        </div>
        <div>
            <label for="Nama_Produk">Nama Produk</label>
            <input type="text" id="Nama_Produk" name="Nama_Produk" value="{{ $produk->Nama_Produk }}" required>
        </div>
        <div>
            <label for="Kategori">Kategori</label>
            <input type="text" id="Kategori" name="Kategori" value="{{ $produk->Kategori }}" required>
        </div>
        <div>
            <label for="Harga">Harga</label>
            <input type="number" id="Harga" name="Harga" value="{{ $produk->Harga }}" required>
        </div>
        <button type="submit">Update Produk</button>
        <a href="{{ route('produk.index') }}" style="margin-left: 10px;">Batal</a>
    </form>
@endsection