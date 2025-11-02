<?php

namespace App\Http\Controllers;

// 1. Import Model Produk
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Untuk validasi

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource. (READ)
     */
    public function index()
    {
        // Ambil semua data produk dari database
        $produks = Produk::all();
        
        // Kirim data ke view
        return view('produk.index', ['produks' => $produks]);
    }

    /**
     * Show the form for creating a new resource. (CREATE - Form)
     */
    public function create()
    {
        // Hanya tampilkan form tambah
        return view('produk.create');
    }

    /**
     * Store a newly created resource in storage. (CREATE - Process)
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            'ID_Produk' => 'required|string|max:8|unique:produk',
            'Nama_Produk' => 'required|string|max:30',
            'Kategori' => 'required|string|max:20',
            'Harga' => 'required|numeric|min:0',
        ]);

        // 2. Jika validasi lolos, simpan ke database
        Produk::create($validatedData);

        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect(route('produk.index'))->with('success', 'Produk baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * (Kita tidak pakai ini, 'index' sudah cukup)
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource. (UPDATE - Form)
     */
    public function edit(string $id)
    {
        // Cari produk berdasarkan ID_Produk
        $produk = Produk::findOrFail($id);
        
        // Kirim data produk ke view edit
        return view('produk.edit', ['produk' => $produk]);
    }

    /**
     * Update the specified resource in storage. (UPDATE - Process)
     */
    public function update(Request $request, string $id)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            'Nama_Produk' => 'required|string|max:30',
            'Kategori' => 'required|string|max:20',
            'Harga' => 'required|numeric|min:0',
        ]);
        
        // (Kita tidak memvalidasi ID_Produk karena itu tidak diubah)

        // 2. Cari produk dan update
        $produk = Produk::findOrFail($id);
        $produk->update($validatedData);

        // 3. Redirect kembali ke halaman index
        return redirect(route('produk.index'))->with('success', 'Data produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage. (DELETE)
     */
    public function destroy(string $id)
    {
        // 1. Cari produk berdasarkan ID
        $produk = Produk::findOrFail($id);
        
        // 2. Hapus produk
        $produk->delete();

        // 3. Redirect kembali ke halaman index
        return redirect(route('produk.index'))->with('success', 'Produk berhasil dihapus!');
    }
}