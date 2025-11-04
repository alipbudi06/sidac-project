@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <style>
        /* CSS ini spesifik untuk form */
        form { background: #fff; padding: 25px; border-radius: 8px; max-width: 600px; margin: auto; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .form-actions {
            display: flex;
            gap: 10px;
        }
        button {
            padding: 11px 15px;
            background-color: #007bff; /* biru normal */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.25s ease, transform 0.1s ease;
        }

        /* efek hover */
        button:hover {
            background-color: #0056b3; /* biru lebih gelap pas di-hover */
            transform: translateY(-1px); /* tombol sedikit naik */
        }

        /* efek saat ditekan */
        button:active {
            transform: translateY(0);
        }

        .error { color: red; font-size: 0.9em; }
        .btn-cancel {
            background-color: #dc3545;
            color: white;
            padding: 10px 17px;
            border: none;
            border-radius: 4px; /* <<< bikin sudutnya tumpul */
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-cancel:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }

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
            <label for="ID_Produk">ID Produk</label>
            <input type="text" id="ID_Produk" name="ID_Produk" value="{{ $newId }}" readonly>
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
        <div class="form-actions">
            <button type="submit">Simpan Produk</button>
            <a href="{{ route('produk.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
@endsection