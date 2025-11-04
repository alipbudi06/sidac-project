@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

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

    <h1>Form Tambah Pelanggan Baru</h1>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pelanggan.store') }}" method="POST">
        @csrf
        <!-- <div>
            <label for="ID_Pelanggan">ID Pelanggan</label>
            <input type="text" id="ID_Pelanggan" name="ID_Pelanggan" value="{{ $newId }}" readonly>
        </div> -->
        <div>
            <label for="Nama_Pelanggan">Nama Pelanggan</label>
            <input type="text" id="Nama_Pelanggan" name="Nama_Pelanggan" value="{{ old('Nama_Pelanggan') }}" required>
        </div>
        <div>
            <label for="Email_Pelanggan">Email (Opsional)</label>
            <input type="email" id="Email_Pelanggan" name="Email_Pelanggan" value="{{ old('Email_Pelanggan') }}">
        </div>
        <div>
            <label for="Kata_Sandi">Kata Sandi (Sesuai ERD: max 13 char)</label>
            <input type="text" id="Kata_Sandi" name="Kata_Sandi" value="{{ old('Kata_Sandi') }}" required>
        </div>
        <button type="submit">Simpan Pelanggan</button>
        <a href="{{ route('pelanggan.index') }}" style="margin-left: 10px;">Batal</a>
    </form>
@endsection