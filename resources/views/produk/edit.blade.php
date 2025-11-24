@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <style>
        /* CSS ini spesifik untuk form */
        form { background: #fff; padding: 25px; border-radius: 8px; max-width: 600px; margin: auto; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        
        /* Tambahkan 'select' di sini agar dropdown gayanya sama dengan input */
        input, select { 
            width: 95%; 
            padding: 8px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            background-color: #fff; /* Pastikan background putih */
        }

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
            border-radius: 4px; 
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
            <input type="text" id="ID_Produk" name="ID_Produk" value="{{ $produk->ID_Produk }}" readonly style="background-color: #e9ecef; cursor: not-allowed;">
        </div>
        <div>
            <label for="Nama_Produk">Nama Produk</label>
            <input type="text" id="Nama_Produk" name="Nama_Produk" value="{{ $produk->Nama_Produk }}" required>
        </div>
        
        {{-- BAGIAN INI SUDAH DIUBAH JADI DROPDOWN --}}
        <div>
            <label for="Kategori">Kategori</label>
            <select id="Kategori" name="Kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Basic" {{ $produk->Kategori == 'Basic' ? 'selected' : '' }}>Basic</option>
                <option value="Flavoured" {{ $produk->Kategori == 'Flavoured' ? 'selected' : '' }}>Flavoured</option>
                <option value="Signature" {{ $produk->Kategori == 'Signature' ? 'selected' : '' }}>Signature</option>
                <option value="Tea-Based" {{ $produk->Kategori == 'Tea-Based' ? 'selected' : '' }}>Tea-Based</option>
                <option value="Powder-Based" {{ $produk->Kategori == 'Powder-Based' ? 'selected' : '' }}>Powder-Based</option>
                <option value="Refreshments" {{ $produk->Kategori == 'Refreshments' ? 'selected' : '' }}>Refreshments</option>
            </select>
        </div>

        <div>
            <label for="Harga">Harga</label>
            <input type="number" id="Harga" name="Harga" value="{{ $produk->Harga }}" required>
        </div>
        <div class="form-actions">
            <button type="submit">Update Produk</button>
            <a href="{{ route('produk.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
@endsection