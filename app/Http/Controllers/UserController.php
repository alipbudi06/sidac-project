<?php

namespace App\Http\Controllers;

// 1. Import Model User dan Hash
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource. (READ)
     */
    public function index()
    {
        // Ambil semua user
        $users = User::all();
        
        // Kirim ke view
        return view('user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource. (CREATE - Form)
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage. (CREATE - Process)
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $validatedData = $request->validate([
            'Nama_User' => 'required|string|max:50',
            'Username' => 'required|string|max:30|unique:users',
            'Email_User' => 'required|email|max:100|unique:users',
            'Password' => 'required|string|min:5', // Pastikan password diisi
            'Role' => 'required|string',
            'Nomor_HP' => 'nullable|string|max:12',
        ]);

        $prefix = strtoupper(substr($validatedData['Role'], 0, 1)); // ambil huruf pertama dari role
        // Kalau mau lebih spesifik:
        if (strtolower($validatedData['Role']) === 'manajer operasional') {
            $prefix = 'M';
        } else {
            $prefix = 'P';
        }

        // 3. Cari ID terakhir berdasarkan prefix tersebut
        $lastUser = User::where('ID_User', 'like', $prefix . '%')
            ->orderBy('ID_User', 'desc')
            ->first();

        if (!$lastUser) {
            $newId = $prefix . '001';
        } else {
            $num = (int) substr($lastUser->ID_User, 1);
            $newId = $prefix . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
        }

        // 2. HASH PASSWORD sebelum disimpan
        $validatedData['Password'] = Hash::make($request->Password);

        $validatedData['ID_User'] = $newId;

        // 3. Simpan ke database
         User::create($validatedData);

        // 4. Redirect
        return redirect(route('user.index'))->with('success', 'User baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Tidak kita gunakan
    }

    /**
     * Show the form for editing the specified resource. (UPDATE - Form)
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage. (UPDATE - Process)
     */
    public function update(Request $request, string $id)
    {
        // 1. Validasi
        $validatedData = $request->validate([
            'Nama_User' => 'required|string|max:50',
            // Pastikan email unik, TAPI abaikan email milik user $id ini
            'Email_User' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users')->ignore($id, 'ID_User'),
            ],
            'Password' => 'nullable|string|min:5', // Password boleh kosong
            'Role' => 'required|string',
            'Nomor_HP' => 'nullable|string|max:12',
        ]);

        // 2. Cek apakah password diisi
        if ($request->filled('Password')) {
            // Jika diisi, hash password baru
            $validatedData['Password'] = Hash::make($request->Password);
        } else {
            // Jika kosong, hapus dari array agar password lama tidak ditimpa
            unset($validatedData['Password']);
        }

        // 3. Cari dan update
        $user = User::findOrFail($id);
        $user->update($validatedData);

        // 4. Redirect
        return redirect(route('user.index'))->with('success', 'Data user berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage. (DELETE)
     */
    public function destroy(string $id)
    {
        // 1. Cari
        $user = User::findOrFail($id);
        
        // 2. Hapus
        $user->delete();

        // 3. Redirect
        return redirect(route('user.index'))->with('success', 'User berhasil dihapus!');
    }
}