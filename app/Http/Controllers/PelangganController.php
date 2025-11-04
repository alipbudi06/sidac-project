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
        // PERBAIKAN:
        // 1. Ambil pelanggan yang 'is_member' = true
        // 2. Gunakan withCount('transaksi') untuk menghitung
        //    transaksi terkait secara otomatis.
        //    Ini akan membuat kolom baru bernama 'transaksi_count'
        
        $pelanggans = Pelanggan::where('is_member', true)
                        ->withCount('transaksi') 
                        ->orderBy('Nama_Pelanggan', 'asc')
                        ->get();
        
        return view('pelanggan.index', ['pelanggans' => $pelanggans]);
    }

    public function create()
    {
        // Fungsi create() dari teman Anda (ini sudah benar)
        $lastPelanggan = \App\Models\Pelanggan::orderBy('ID_Pelanggan', 'desc')->first();
        if (!$lastPelanggan) {
            $newId = 'C001';
        } else {
            $num = (int) substr($lastPelanggan->ID_Pelanggan, 1);
            $newId = 'C' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
        }
        return view('pelanggan.create', compact('newId'));
    }

    /**
     * Ini adalah FUNGSI STORE() YANG SUDAH DIGABUNG (MERGED)
     */
    public function store(Request $request)
    {
        // 1. Ambil Logika ID Otomatis (dari Teman Anda)
        $lastPelanggan = \App\Models\Pelanggan::orderBy('ID_Pelanggan', 'desc')->first();
        $newId = $lastPelanggan ? 'C' . str_pad(((int) substr($lastPelanggan->ID_Pelanggan, 1)) + 1, 3, '0', STR_PAD_LEFT) : 'C001';

        // 2. Validasi Data (HAPUS validasi ID_Pelanggan, karena sudah otomatis)
        $validatedData = $request->validate([
            'Nama_Pelanggan' => 'required|string|max:20',
            'Email_Pelanggan' => 'nullable|email|max:100|unique:pelanggan,Email_Pelanggan',
            'Kata_Sandi' => 'required|string|max:13',
        ]);

        // 3. Gabungkan SEMUA data
        $validatedData['ID_Pelanggan'] = $newId; // (dari Teman Anda)
        $validatedData['is_member'] = true; // (dari Anda)
        $validatedData['Frekuensi_Pembelian'] = 0; // (dari Anda)

        // 4. Simpan ke database
        Pelanggan::create($validatedData);

        // 5. Redirect
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