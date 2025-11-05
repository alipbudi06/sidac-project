<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller; 

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        // Membaca dari kolom DB (BUKAN withCount)
        $search = $request->query('search');
        $query = Pelanggan::where('is_member', true); 
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('ID_Pelanggan', 'LIKE', "%{$search}%")
                  ->orWhere('Nama_Pelanggan', 'LIKE', "%{$search}%")
                  ->orWhere('Email_Pelanggan', 'LIKE', "%{$search}%");
            });
        }
        $pelanggans = $query->orderBy('Nama_Pelanggan', 'asc')->paginate(10);
        $pelanggans->appends(['search' => $search]);
        
        return view('pelanggan.index', [
            'pelanggans' => $pelanggans,
            'search' => $search
        ]);
    }
    
    public function create()
    {
        $lastPelanggan = \App\Models\Pelanggan::orderBy('ID_Pelanggan', 'desc')->first();
        $newId = !$lastPelanggan ? 'C001' : 'C' . str_pad(((int) substr($lastPelanggan->ID_Pelanggan, 1)) + 1, 3, '0', STR_PAD_LEFT);
        return view('pelanggan.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $lastPelanggan = \App\Models\Pelanggan::orderBy('ID_Pelanggan', 'desc')->first();
        $newId = !$lastPelanggan ? 'C001' : 'C' . str_pad(((int) substr($lastPelanggan->ID_Pelanggan, 1)) + 1, 3, '0', STR_PAD_LEFT);
        $validatedData = $request->validate([
            'Nama_Pelanggan' => 'required|string|max:20',
            'Email_Pelanggan' => 'nullable|email|max:100|unique:pelanggan,Email_Pelanggan',
            'Kata_Sandi' => 'required|string|max:13',
        ]);
        $validatedData['ID_Pelanggan'] = $newId; 
        $validatedData['is_member'] = true; 
        $validatedData['Frekuensi_Pembelian'] = 0; 
        Pelanggan::create($validatedData);
        return redirect(route('pelanggan.index'))->with('success', 'Member baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::withCount('transaksi')->findOrFail($id);
        // 'transaksi_count' akan otomatis berisi jumlah transaksi

        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, string $id)
    {
        // Hapus 'Frekuensi_Pembelian' dari validasi
        $validatedData = $request->validate([
            'Nama_Pelanggan' => 'required|string|max:20',
            'Email_Pelanggan' => 'nullable|email|max:100|unique:pelanggan,Email_Pelanggan,' . $id . ',ID_Pelanggan',
            'Kata_Sandi' => 'required|string|max:13',
            // 'Frekuensi_Pembelian' => ... (DIHAPUS)
        ]);
        
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($validatedData); // Hanya update data ini
        return redirect(route('pelanggan.index'))->with('success', 'Data member berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();
        return redirect(route('pelanggan.index'))->with('success', 'Member berhasil dihapus!');
    }
}