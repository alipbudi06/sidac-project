<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel; // <-- IMPORT BARU
use App\Imports\ProdukImport;      // <-- IMPORT BARU
use Illuminate\Support\Facades\Log;

class ProdukController extends Controller
{
    /**
     * MODIFIKASI FUNGSI INDEX UNTUK FILTER
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Produk::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ID_Produk', 'LIKE', "%{$search}%")
                    ->orWhere('Nama_Produk', 'LIKE', "%{$search}%")
                    ->orWhere('Kategori', 'LIKE', "%{$search}%");
            });
        }

        $produks = $query->paginate(10)->appends(['search' => $search]);

        return view('produk.index', [
            'produks' => $produks,
            'search' => $search
        ]);
    }

    public function create()
    {
        $lastProduk = \App\Models\Produk::orderBy('ID_Produk', 'desc')->first();
        $newId = $lastProduk ? 'P' . str_pad(((int) substr($lastProduk->ID_Produk, 1)) + 1, 3, '0', STR_PAD_LEFT) : 'P001';
        return view('produk.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $lastProduk = \App\Models\Produk::orderBy('ID_Produk', 'desc')->first();
        $newId = $lastProduk ? 'P' . str_pad(((int) substr($lastProduk->ID_Produk, 1)) + 1, 3, '0', STR_PAD_LEFT) : 'P001';
        $validatedData = $request->validate([
            'Nama_Produk' => 'required|string|max:30',
            'Kategori' => 'required|string|max:20',
            'Harga' => 'required|numeric|min:0',
        ]);
        $validatedData['ID_Produk'] = $newId;
        Produk::create($validatedData);
        return redirect(route('produk.index'))->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.edit', ['produk' => $produk]);
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'Nama_Produk' => 'required|string|max:30',
            'Kategori' => 'required|string|max:20',
            'Harga' => 'required|numeric|min:0',
        ]);
        $produk = Produk::findOrFail($id);
        $produk->update($validatedData);
        return redirect(route('produk.index'))->with('success', 'Data produk berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
        return redirect(route('produk.index'))->with('success', 'Produk berhasil dihapus!');
    }

    // ===================================
    // === FUNGSI BARU UNTUK IMPORT ======
    // ===================================

    /**
     * Menampilkan halaman/form untuk upload file.
     */
    public function showImportForm()
    {
        return view('produk.import');
    }

    /**
     * Memproses file yang di-upload.
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file_produk' => 'required|mimes:xls,xlsx,csv,txt'
        ]);
        
        try {
            Excel::import(new ProdukImport, $request->file('file_produk'));
            return redirect(route('produk.index'))->with('success', 'Data produk berhasil di-import!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Import gagal: ' . $e->getMessage()]);
        }
    }
}
