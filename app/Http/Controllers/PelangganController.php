<?php

namespace App\Http\Controllers;

// 1. Import Model Pelanggan
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource. (READ)
     */
    public function index()
    {
        // Ambil semua data pelanggan
        $pelanggans = Pelanggan::all();
        
        // Kirim ke view
        return view('pelanggan.index', ['pelanggans' => $pelanggans]);
    }

    /**
     * Show the form for creating a new resource. (CREATE - Form)
     */
    public function create()
    {
        return view('pelanggan.create');
    }

    /**
     * Store a newly created resource in storage. (CREATE - Process)
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $validatedData = $request->validate([
            'ID_Pelanggan' => 'required|string|max:8|unique:pelanggan',
            'Nama_Pelanggan' => 'required|string|max:20',
            'Email_Pelanggan' => 'nullable|email|max:100',
            'Kata_Sandi' => 'required|string|max:13',
            // Frekuensi pembelian tidak perlu divalidasi, kita set default 0
        ]);

        // 2. Simpan ke database
        Pelanggan::create($validatedData);

        // 3. Redirect
        return redirect(route('pelanggan.index'))->with('success', 'Pelanggan baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Tidak kita gunakan
    }

    /**
     * Show the form for editing the specified resource. (UPDATE - Form)
     */
    public function edit(string $id)
    {
        // Cari pelanggan
        $pelanggan = Pelanggan::findOrFail($id);
        
        // Kirim ke view edit
        return view('pelanggan.edit', ['pelanggan' => $pelanggan]);
    }

    /**
     * Update the specified resource in storage. (UPDATE - Process)
     */
    public function update(Request $request, string $id)
    {
        // 1. Validasi
        $validatedData = $request->validate([
            'Nama_Pelanggan' => 'required|string|max:20',
            'Email_Pelanggan' => 'nullable|email|max:100',
            'Kata_Sandi' => 'required|string|max:13',
            'Frekuensi_Pembelian' => 'required|integer|min:0',
        ]);
        
        // 2. Cari dan update
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($validatedData);

        // 3. Redirect
        return redirect(route('pelanggan.index'))->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage. (DELETE)
     */
    public function destroy(string $id)
    {
        // 1. Cari
        $pelanggan = Pelanggan::findOrFail($id);
        
        // 2. Hapus
        $pelanggan->delete();

        // 3. Redirect
        return redirect(route('pelanggan.index'))->with('success', 'Pelanggan berhasil dihapus!');
    }
}