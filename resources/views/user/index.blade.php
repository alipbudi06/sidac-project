@extends('layouts.app')

@section('title', 'Kelola User')

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
        <h1>Kelola Data User</h1>
        <a href="{{ route('user.create') }}" class="btn btn-tambah">Tambah User Baru</a>
    </div>
    
    @if (session('success'))
        <div style="color: green; margin-top: 10px;">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID User</th>
                <th>Nama User</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->ID_User }}</td>
                <td>{{ $user->Nama_User }}</td>
                <td>{{ $user->Username }}</td>
                <td>{{ $user->Email_User }}</td>
                <td>{{ $user->Role }}</td>
                <td>
                    <a href="{{ route('user.edit', $user->ID_User) }}" class="btn btn-edit">Edit</a>
                    
                    <form action="{{ route('user.destroy', $user->ID_User) }}" method="POST" style="display:inline;">
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