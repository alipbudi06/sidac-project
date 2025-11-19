@extends('layouts.app')
@section('title', 'Edit Pelanggan')
@section('content')
    <style>
        form { background: #fff; padding: 25px; border-radius: 8px; max-width: 600px; margin: auto; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-size: 0.9em; }
    </style>

    <h1>Form Edit Pelanggan</h1>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pelanggan.update', $pelanggan->ID_Pelanggan) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="ID_Pelanggan">ID Pelanggan</label>
            <input type="text" id="ID_Pelanggan" name="ID_Pelanggan" value="{{ $pelanggan->ID_Pelanggan }}" readonly>
        </div>
        <div>
            <label for="Nama_Pelanggan">Nama Pelanggan</label>
            <input type="text" id="Nama_Pelanggan" name="Nama_Pelanggan" value="{{ $pelanggan->Nama_Pelanggan }}" required>
        </div>
        <div>
            <label for="Email_Pelanggan">Email (Opsional)</label>
            <input type="email" id="Email_Pelanggan" name="Email_Pelanggan" value="{{ $pelanggan->Email_Pelanggan }}">
        </div>
        <div>
            <label for="Kata_Sandi">Kata Sandi</label>
            <input type="text" id="Kata_Sandi" name="Kata_Sandi" value="{{ $pelanggan->Kata_Sandi }}" required>
        </div>
        
        <!-- DI-DISABLE AGAR TIDAK BISA DIEDIT MANUAL -->
        <div>
            <label for="Frekuensi_Pembelian">Frekuensi Pembelian (Otomatis)</label>
            <input type="number" id="Frekuensi_Pembelian" name="Frekuensi_Pembelian" 
                   value="{{ $pelanggan->Frekuensi_Pembelian }}" readonly disabled 
                   style="background-color: #eee;">
            <small style="color: #666;">Jumlah ini diupdate otomatis setiap kali transaksi dilakukan.</small>
        </div>

        <button type="submit">Update Pelanggan</button>
        <a href="{{ route('pelanggan.index') }}" style="margin-left: 10px;">Batal</a>
    </form>
@endsection