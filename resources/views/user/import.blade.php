@extends('layouts.app')

@section('title', 'Import User')

@section('content')
    <style>
        form { background: #fff; padding: 25px; border-radius: 8px; max-width: 600px; margin: auto; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="file"] { width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .alert-info { color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb; }
    </style>
    
    <h1>Form Import Data User</h1>

    <div class="alert alert-info">
        <strong>PENTING:</strong> Pastikan file Excel/CSV Anda memiliki header: `nama_user`, `username`, `email_user`, `password`, `role` (isi persis 'Manajer Operasional' atau 'Pegawai'), dan `nomor_hp` (opsional).
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Error!</strong> Terjadi masalah dengan file Anda.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.import.process') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="file_user">Pilih File (Excel/CSV)</label>
            <input type="file" id="file_user" name="file_user" required>
        </div>
        
        <button type="submit">Upload dan Import Data</button>
        <a href="{{ route('user.index') }}" style="margin-left: 10px;">Batal</a>
    </form>
@endsection