<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller; 
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PelangganImport;
use App\Models\Transaksi; // Penting untuk sync

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        // ===========================================
        // KEMBALIKAN SEPERTI SEMULA:
        // Sekarang kita bisa percaya kolom 'Frekuensi_Pembelian' di DB
        // ===========================================
        $search = $request->query('search');
        
        // Hapus withCount('transaksi')
        $query = Pelanggan::where('is_member', true);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('ID_Pelanggan', 'LIKE', "%{$search}%")
                  ->orWhere('Nama_Pelanggan', 'LIKE', "%{$search}%")
                  ->orWhere('Email_Pelanggan', 'LIKE', "%{$search}%");
            });
        }
        $pelanggans = $query->orderBy('Nama_Pelanggan', 'asc')->paginate(10);
        
        return view('pelanggan.index', [
            'pelanggans' => $pelanggans,
            'search' => $search
        ]);
    }
    
    // public function create()
    // {
    //     $lastPelanggan = \App\Models\Pelanggan::orderBy('ID_Pelanggan', 'desc')->first();
    //     $newId = !$lastPelanggan ? 'C001' : 'C' . str_pad(((int) substr($lastPelanggan->ID_Pelanggan, 1)) + 1, 3, '0', STR_PAD_LEFT);
    //     return view('pelanggan.create', compact('newId'));
    // }

    public function store(Request $request)
    {
        $lastPelanggan = \App\Models\Pelanggan::orderBy('ID_Pelanggan', 'desc')->first();
        $newId = !$lastPelanggan ? 'C001' : 'C' . str_pad(((int) substr($lastPelanggan->ID_Pelanggan, 1)) + 1, 3, '0', STR_PAD_LEFT);

        $validatedData = $request->validate([
            'Nama_Pelanggan' => 'required|string|max:20',
            'Email_Pelanggan' => 'nullable|email|max:100|unique:pelanggan,Email_Pelanggan',
        ]);

        $validatedData['ID_Pelanggan'] = $newId;
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
        // Hapus 'Frekuensi_Pembelian' dari validasi agar tidak bisa di-hack
        $validatedData = $request->validate([
            'Nama_Pelanggan' => 'required|string|max:20',
            'Email_Pelanggan' => 'nullable|email|max:100|unique:pelanggan,Email_Pelanggan,' . $id . ',ID_Pelanggan',
        ]);
        
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($validatedData); // Hanya update data profil, bukan frekuensi
        return redirect(route('pelanggan.index'))->with('success', 'Data member berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();
        return redirect(route('pelanggan.index'))->with('success', 'Member berhasil dihapus!');
    }

    public function showImportForm()
    {
        return view('pelanggan.import');
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'file_pelanggan' => 'required|mimes:xls,xlsx,csv,txt'
        ]);

        try {
            Excel::import(new PelangganImport, $request->file('file_pelanggan'));
            
            // SINKRONISASI: Hitung ulang setelah import juga
            $this->syncFrekuensiPembelian();

            return redirect(route('pelanggan.index'))->with('success', 'Data pelanggan berhasil di-import!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Import gagal: ' . $e->getMessage()]);
        }
    }

        private function syncFrekuensiPembelian()
    {
        $allPelanggans = Pelanggan::all();
        foreach ($allPelanggans as $pelanggan) {
            $countTransaksi = Transaksi::where('ID_Pelanggan', $pelanggan->ID_Pelanggan)->count();

            $pelanggan->Frekuensi_Pembelian =
                (int) $pelanggan->Frekuensi_Pembelian;

            $pelanggan->save();
        }
    }
}