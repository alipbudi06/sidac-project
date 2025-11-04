<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller; // Pastikan ini di-import

class PelangganController extends Controller
{
    public function index()
    {
        // PERBAIKAN: Hanya tampilkan yang 'is_member' = true
        $pelanggans = Pelanggan::where('is_member', true)
                        ->orderBy('Nama_Pelanggan', 'asc')
                        ->get();
        
        return view('pelanggan.index', ['pelanggans' => $pelanggans]);
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ID_Pelanggan' => 'required|string|max:8|unique:pelanggan',
            'Nama_Pelanggan' => 'required|string|max:20',
            'Email_Pelanggan' => 'nullable|email|max:100|unique:pelanggan,Email_Pelanggan',
            'Kata_Sandi' => 'required|string|max:13',
        ]);

        // PERBAIKAN: Otomatis set sebagai member
        $validatedData['is_member'] = true;
        $validatedData['Frekuensi_Pembelian'] = 0; 

        Pelanggan::create($validatedData);
        return redirect(route('pelanggan.index'))->with('success', 'Member baru berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        if ($pelanggan->is_member == false) {
            abort(404);
        }
        return view('pelanggan.edit', ['pelanggan' => $pelanggan]);
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'Nama_Pelanggan' => 'required|string|max:20',
            'Email_Pelanggan' => 'nullable|email|max:100|unique:pelanggan,Email_Pelanggan,' . $id . ',ID_Pelanggan',
            'Kata_Sandi' => 'required|string|max:13',
            'Frekuensi_Pembelian' => 'required|integer|min:0',
        ]);
        
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($validatedData);
        return redirect(route('pelanggan.index'))->with('success', 'Data member berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();
        return redirect(route('pelanggan.index'))->with('success', 'Member berhasil dihapus!');
    }
}