<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller; // Pastikan ini di-import
// use Illuminate\Support\Facades\Gate; // (Kita matikan dulu, sesuai reset)

class ProdukController extends Controller
{
    public function index()
    {
        // Gate::authorize('is-pegawai-or-manajer');
        $produks = Produk::all();
        return view('produk.index', ['produks' => $produks]);
    }

    public function create()
    {
        // Gate::authorize('is-manajer');
        
        // Logika ID Otomatis dari Teman Anda
        $lastProduk = \App\Models\Produk::orderBy('ID_Produk', 'desc')->first();
        $newId = $lastProduk ? 'P' . str_pad(((int) substr($lastProduk->ID_Produk, 1)) + 1, 3, '0', STR_PAD_LEFT) : 'P001';
        
        return view('produk.create', compact('newId'));
    }

    public function store(Request $request)
    {
        // Gate::authorize('is-manajer');

        // Logika ID Otomatis dari Teman Anda
        $lastProduk = \App\Models\Produk::orderBy('ID_Produk', 'desc')->first();
        $newId = $lastProduk ? 'P' . str_pad(((int) substr($lastProduk->ID_Produk, 1)) + 1, 3, '0', STR_PAD_LEFT) : 'P001';

        // Validasi (tanpa ID_Produk)
        $validatedData = $request->validate([
            'Nama_Produk' => 'required|string|max:30',
            'Kategori' => 'required|string|max:20',
            'Harga' => 'required|numeric|min:0',
        ]);

        // Gabungkan data
        $validatedData['ID_Produk'] = $newId; 

        Produk::create($validatedData);
        
        return redirect(route('produk.index'))->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        // Gate::authorize('is-pegawai-or-manajer');
    }

    public function edit(string $id)
    {
        // Gate::authorize('is-manajer');
        $produk = Produk::findOrFail($id);
        return view('produk.edit', ['produk' => $produk]);
    }

    public function update(Request $request, string $id)
    {
        // Gate::authorize('is-manajer');
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
        // Gate::authorize('is-manajer');
        $produk = Produk::findOrFail($id);
        $produk->delete();
        return redirect(route('produk.index'))->with('success', 'Produk berhasil dihapus!');
    }
}