<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
// use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        // Gate::authorize('is-manajer');
        $users = User::all();
        return view('user.index', ['users' => $users]);
    }

    public function create()
    {
        // Gate::authorize('is-manajer');
        
        // Logika ID Otomatis
        $lastUser = \App\Models\User::orderBy('ID_User', 'desc')->first();
        if (!$lastUser) {
            $newId = 'U001'; // Asumsi awalan 'U'
        } else {
             $prefix = $lastUser->ID_User[0]; // Ambil awalan (M atau P)
             $num = (int) substr($lastUser->ID_User, 1);
             $newId = $prefix . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
        }
        
        return view('user.create', compact('newId'));
    }

    public function store(Request $request)
    {
        // Gate::authorize('is-manajer');
        
        // Logika ID Otomatis
        $lastUser = \App\Models\User::orderBy('ID_User', 'desc')->first();
        if (!$lastUser) {
            $newId = 'U001'; 
        } else {
             $prefix = $request->Role == 'Manajer Operasional' ? 'M' : 'P';
             $num = (int) substr($lastUser->ID_User, 1);
             $newId = $prefix . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
        }

        $validatedData = $request->validate([
            // 'ID_User' => 'required|string|max:8|unique:users', // Dihapus
            'Nama_User' => 'required|string|max:50',
            'Username' => 'required|string|max:30|unique:users',
            'Email_User' => 'required|email|max:100|unique:users',
            'Password' => 'required|string|min:5',
            'Role' => 'required|string',
            'Nomor_HP' => 'nullable|string|max:12',
        ]);
        
        $validatedData['ID_User'] = $newId; // Tambahkan ID baru
        $validatedData['Password'] = Hash::make($request->Password);
        User::create($validatedData);
        return redirect(route('user.index'))->with('success', 'User baru berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        // Gate::authorize('is-manajer');
    }

    public function edit(string $id)
    {
        // Gate::authorize('is-manajer');
        $user = User::findOrFail($id);
        return view('user.edit', ['user' => $user]);
    }

    public function update(Request $request, string $id)
    {
        // Gate::authorize('is-manajer');
        $validatedData = $request->validate([
            'Nama_User' => 'required|string|max:50',
            'Email_User' => ['required', 'email', 'max:100', Rule::unique('users')->ignore($id, 'ID_User')],
            'Password' => 'nullable|string|min:5',
            'Role' => 'required|string',
            'Nomor_HP' => 'nullable|string|max:12',
        ]);
        if ($request->filled('Password')) {
            $validatedData['Password'] = Hash::make($request->Password);
        } else {
            unset($validatedData['Password']);
        }
        $user = User::findOrFail($id);
        $user->update($validatedData);
        return redirect(route('user.index'))->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        // Gate::authorize('is-manajer');
        $user = User::findOrFail($id);
        $user->delete();
        return redirect(route('user.index'))->with('success', 'User berhasil dihapus!');
    }
}