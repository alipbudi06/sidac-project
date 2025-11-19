<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Transaksi; // <-- 1. IMPORT TRANSAKSI (untuk sync)
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller; 
use Maatwebsite\Excel\Facades\Excel; // <-- 2. IMPORT EXCEL
use App\Imports\PelangganImport;      // <-- 3. IMPORT KELAS IMPORT

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        // Kita HANYA ambil member
        $query = Pelanggan::where('is_member', true);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('ID_Pelanggan', 'LIKE', "%{$search}%")
                  ->orWhere('Nama_Pelanggan', 'LIKE', "%{$search}%")
                  ->orWhere('Email_Pelanggan', 'LIKE', "%{$search}%");
            });
        }
        
        // ===================================================
        // PERBAIKAN: Gunakan paginate() untuk memperbaiki error ".links()"
        // ===================================================
        $pelanggans = $query->orderBy('Nama_Pelanggan', 'asc')->paginate(10);
        
        return view('pelanggan.index', [
            'pelanggans' => $pelanggans,
            'search' => $search
        ]);
    }

    public function create()
    {
        // Logika Auto-ID dari teman Anda
        $lastPelanggan = \App\Models\Pelanggan::orderBy('ID_Pelanggan', 'desc')->first();
        $newId = !$lastPelanggan ? 'C001' : 'C' . str_pad(((int) substr($lastPelanggan->ID_Pelanggan, 1)) + 1, 3, '0', STR_PAD_LEFT);
        return view('pelanggan.create', compact('newId'));
    }

    public function store(Request $request)
    {
        // Logika Auto-ID dari teman Anda
        $lastPelanggan = \App\Models\Pelanggan::orderBy('ID_Pelanggan', 'desc')->first();
        $newId = !$lastPelanggan ? 'C001' : 'C' . str_pad(((int) substr($lastPelanggan->ID_Pelanggan, 1)) + 1, 3, '0', STR_PAD_LEFT);

        $validatedData = $request->validate([
            'Nama_Pelanggan' => 'required|string|max:20',
            'Email_Pelanggan' => 'nullable|email|max:100|unique:pelanggan,Email_Pelanggan',
            'Kata_Sandi' => 'required|string|max:13',
        ]);

        // Logika gabungan (Merge)
        $validatedData['ID_Pelanggan'] = $newId; 
        $validatedData['is_member'] = true; 
        $validatedData['Frekuensi_Pembelian'] = 0; // Mulai dari 0

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
        // Validasi (tanpa Frekuensi Pembelian, karena itu otomatis)
        $validatedData = $request->validate([
            'Nama_Pelanggan' => 'required|string|max:20',
            'Email_Pelanggan' => 'nullable|email|max:100|unique:pelanggan,Email_Pelanggan,' . $id . ',ID_Pelanggan',
            'Kata_Sandi' => 'required|string|max:13',
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

    // ===================================================
    // === FUNGSI YANG HILANG (INI PERBAIKANNYA) ===
    // ===================================================

    /**
     * Menampilkan halaman/form untuk upload file.
     */
    public function showImportForm()
    {
        return view('pelanggan.import');
    }

    /**
     * Memproses file yang di-upload.
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file_pelanggan' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {
            Excel::import(new PelangganImport, $request->file('file_pelanggan'));
            
            // SINKRONISASI: Hitung ulang frekuensi setelah import
            $this->syncFrekuensiPembelian();

            return redirect(route('pelanggan.index'))->with('success', 'Data pelanggan berhasil di-import!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Import gagal: ' . $e->getMessage()]);
        }
    }

    /**
     * Fungsi helper untuk sinkronisasi Frekuensi Pembelian
     */
    private function syncFrekuensiPembelian()
    {
        $allPelanggans = Pelanggan::all();
        foreach ($allPelanggans as $pelanggan) {
            $count = Transaksi::where('ID_Pelanggan', $pelanggan->ID_Pelanggan)->count();
            $pelanggan->Frekuensi_Pembelian = $count;
            $pelanggan->save();
        }
    }
}