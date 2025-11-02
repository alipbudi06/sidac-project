@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <style>
        /* CSS ini spesifik untuk form */
        form { background: #fff; padding: 25px; border-radius: 8px; max-width: 600px; margin: auto; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-size: 0.9em; }
        .help-text { font-size: 0.8em; color: #555; }
    </style>

    <h1>Form Edit User</h1>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.update', $user->ID_User) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="ID_User">ID User</label>
            <input type="text" id="ID_User" name="ID_User" value="{{ $user->ID_User }}" readonly>
        </div>
        <div>
            <label for="Nama_User">Nama Lengkap</label>
            <input type="text" id="Nama_User" name="Nama_User" value="{{ $user->Nama_User }}" required>
        </div>
        <div>
            <label for="Username">Username</label>
            <input type="text" id="Username" name="Username" value="{{ $user->Username }}" readonly>
        </div>
         <div>
            <label for="Email_User">Email</label>
            <input type="email" id="Email_User" name="Email_User" value="{{ $user->Email_User }}" required>
        </div>
        <div>
            <label for="Password">Password</label>
            <input type="password" id="Password" name="Password">
            <small class="help-text">Kosongkan jika tidak ingin mengubah password.</small>
        </div>
        <div>
            <label for="Role">Role</label>
            <select id="Role" name="Role" required>
                <option value="Manajer Operasional" {{ $user->Role == 'Manajer Operasional' ? 'selected' : '' }}>Manajer Operasional</option>
                <option value="Pegawai" {{ $user->Role == 'Pegawai' ? 'selected' : '' }}>Pegawai</option>
            </select>
        </div>
        <div>
            <label for="Nomor_HP">Nomor HP (Opsional)</label>
            <input type="text" id="Nomor_HP" name="Nomor_HP" value="{{ $user->Nomor_HP }}">
        </div>
        <button type="submit">Update User</button>
        <a href="{{ route('user.index') }}" style="margin-left: 10px;">Batal</a>
    </form>
@endsection