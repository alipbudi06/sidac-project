<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller; // Pastikan ini di-import
use Maatwebsite\Excel\Facades\Excel; // <-- IMPORT BARU
use App\Imports\UserImport;      // <-- IMPORT BARU

class UserController extends Controller
{
    /**
     * MODIFIKASI FUNGSI INDEX UNTUK FILTER
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $query = User::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('ID_User', 'LIKE', "%{$search}%")
                  ->orWhere('Nama_User', 'LIKE', "%{$search}%")
                  ->orWhere('Username', 'LIKE', "%{$search}%")
                  ->orWhere('Email_User', 'LIKE', "%{$search}%")
                  ->orWhere('Role', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->get();
        
        return view('user.index', [
            'users' => $users,
            'search' => $search
        ]);
    }

    public function create()
    {
        $lastUser = \App\Models\User::orderBy('ID_User', 'desc')->first();
        if (!$lastUser) {
            $newId = 'M001'; // Default
        } else {
             $num = (int) substr($lastUser->ID_User, 1);
             $newId = 'U' . str_pad($num + 1, 3, '0', STR_PAD_LEFT); // 'U' sebagai placeholder
        }
        return view('user.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $lastUser = \App\Models\User::orderBy('ID_User', 'desc')->first();
        $num = $lastUser ? (int) substr($lastUser->ID_User, 1) : 0;
        $prefix = $request->Role == 'Manajer Operasional' ? 'M' : 'P';
        $newId = $prefix . str_pad($num + 1, 3, '0', STR_PAD_LEFT);

        $validatedData = $request->validate([
            'Nama_User' => 'required|string|max:50',
            'Username' => 'required|string|max:30|unique:users',
            'Email_User' => 'required|email|max:100|unique:users',
            'Password' => 'required|string|min:5',
            'Role' => 'required|string',
            'Nomor_HP' => 'nullable|string|max:12',
        ]);
        
        $validatedData['ID_User'] = $newId;
        $validatedData['Password'] = Hash::make($request->Password);
        User::create($validatedData);
        return redirect(route('user.index'))->with('success', 'User baru berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', ['user' => $user]);
    }

    public function update(Request $request, string $id)
    {
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
        $user = User::findOrFail($id);
        $user->delete();
        return redirect(route('user.index'))->with('success', 'User berhasil dihapus!');
    }

    // ===================================
    // === FUNGSI BARU UNTUK IMPORT ======
    // ===================================

    /**
     * Menampilkan halaman/form untuk upload file.
     */
    public function showImportForm()
    {
        return view('user.import');
    }

    /**
     * Memproses file yang di-upload.
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file_user' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {
            Excel::import(new UserImport, $request->file('file_user'));
            return redirect(route('user.index'))->with('success', 'Data user berhasil di-import!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Import gagal: ' . $e->getMessage()]);
        }
    }
}