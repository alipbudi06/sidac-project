@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    /* CSS ini sekarang spesifik HANYA untuk konten dashboard.
       Layout utama (sidebar, dll) diatur oleh layouts.app
    */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .sub-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .sub-header .title { display: flex; align-items: center; gap: 10px; }
    .sub-header .title .icon {
        background-color: #0d6efd;
        color: white;
        padding: 10px;
        border-radius: 8px;
        font-size: 1.2em;
    }
    .sub-header h1 { margin: 0; font-size: 1.5em; }
    .sub-header p { margin: 0; color: #555; }
    .btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9em;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        display: inline-block;
        margin-left: 8px;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .btn-export {
        background: linear-gradient(135deg, #e65c00, #ff9900);
        color: #fff;
        padding: 10px 15px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        border: none;
        cursor: pointer;
        font-size: 0.9em;
    }
    
    .card {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    
    .filter-card h2 {
        margin-top: 0;
        font-size: 1.1em;
        margin-bottom: 15px;
    }
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 kolom sama besar */
        gap: 20px;
        align-items: end; /* tombol sejajar bawah input */
    }
    .filter-group { position: relative; display: flex; flex-direction: column; }
    .filter-group label {
        font-size: 0.8em;
        color: #555;
        margin-bottom: 5px;
    }
    .filter-group input, .filter-group select {
        appearance: none; /* Hilangkan ikon bawaan browser */
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: #fff;
        background-image: none;
        padding: 8px 15px 8px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 0.9em;
        box-sizing: border-box;
    }
    .filter-group .input-icon {
        position: absolute;
        right: 12px;       /* geser sedikit dari kanan */
        bottom: 12px;      /* sejajarkan dengan teks select */
        color: #888;
        pointer-events: none; /* biar klik tetap tembus ke select */
        font-size: 0.8em;
        z-index: 2;
    }
    .btn-filter {
        background: #0d6efd;
        justify-content: space-between;
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9em;
        width: 100%;
    }
    .btn-filter:hover {
        background-color: #0a58ca !important;
        color: white !important;
    }
    .filter-buttons {
    display: flex;
    justify-content: space-between; /* tombol kiri-kanan */
    align-items: center;
    margin-top: 15px;
    }

    .filter-buttons .btn-filter {
        flex: 1;
        max-width: 200px;
    }

    .filter-buttons .btn-secondary {
        flex: 1;                /* bagi ruang sama besar */
        max-width: 220px;       /* biar gak kelebaran */
        text-align: center;
        padding: 10px 0;        /* tinggi konsisten */
        font-weight: 600;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .stat-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-radius: 8px;
        color: white;
    }
    .stat-card-blue { background: linear-gradient(135deg, #0048b5, #007bff); color: #fff; }
    .stat-card-orange { background: linear-gradient(135deg, #e65c00, #ff9900); color: #fff; }
    .stat-card .icon { font-size: 2em; opacity: 0.7; }
    .stat-card h3 { margin: 0; font-size: 1.1em; font-weight: 500; }
    .stat-card p { margin: 5px 0 0 0; font-size: 1.5em; font-weight: bold; }
    .stat-card .empty-text { font-size: 0.9em; margin-top: 8px; }
    
    .chart-card .chart-placeholder {
        width: 100%;
        height: 250px;
        border: 1px dashed #ccc;
        display: grid;
        place-items: center;
        color: #aaa;
        border-radius: 4px;
    }
    
    .top5-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .top5-card h3 {
        margin-top: 0;
        font-size: 1.2em;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .top5-card .icon { color: #fd7e14; }
    .top5-card table { width: 100%; border-collapse: collapse; }
    .top5-card th, .top5-card td { padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
    .top5-card th { text-align: left; color: #555; }
    .top5-card .empty-text { color: #888; font-style: italic; }

</style>

<section class="sub-header">
    <div class="title">
        <span class="icon"><i class="fa fa-chart-bar"></i></span>
        <div>
            <h1>Dashboard SIDAC</h1>
            <p>Monitor Performa Bisnis Anda (Welcome, <strong>{{ $namaUser }}</strong>!)</p>
        </div>
    </div>
    
    @if($roleUser == 'Manajer Operasional')
        <button class="btn-export"><i class="fa fa-file-pdf"></i> Ekspor PDF</button>
    @endif
</section>

<section class="filter-card card">
    <h2>Filter Dashboard</h2>
    <form method="GET" action="{{ route('dashboard') }}">
        <div class="filter-grid">
            <div class="filter-group">
                <label for="tgl_mulai">Tanggal Mulai</label>
                <input type="date" id="tgl_mulai" name="tgl_mulai" value="{{ request('tgl_mulai') }}">
            </div>

            <div class="filter-group">
                <label for="tgl_selesai">Tanggal Selesai</label>
                <input type="date" id="tgl_selesai" name="tgl_selesai" value="{{ request('tgl_selesai') }}">
            </div>

            <div class="filter-group">
                <label for="produk_id"><i class="fa fa-box"></i> Produk</label>
                <select name="produk_id" id="produk_id">
                    <option value="">Semua Produk</option>
                    @foreach($produks_list as $produk)
                        <option value="{{ $produk->ID_Produk }}" 
                            {{ ($produk_filter ?? '') == $produk->ID_Produk ? 'selected' : '' }}>
                            {{ $produk->Nama_Produk }}
                        </option>
                    @endforeach
                </select>
                <i class="fa fa-chevron-down input-icon"></i>
            </div>
            <div class="filter-buttons">
                <button type="submit" class="btn-filter">
                    <i class="fa fa-search"></i> Terapkan
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fa fa-undo"></i> Reset
                </a>
            </div>
        </div>
    </form>
</section>


<section class="stats-grid">
    <div class="stat-card stat-card-blue">
        <div>
            <h3>Total Transaksi</h3>
            @if(isset($statistik->total_transaksi) && $statistik->total_transaksi > 0)
                <p>{{ $statistik->total_transaksi }}</p>
            @else
                <p class="empty-text">Belum ada transaksi pada periode ini</p>
            @endif
        </div>
        <div class="icon">
            <i class="fa fa-shopping-cart"></i>
        </div>
    </div>
    <div class="stat-card stat-card-orange">
        <div>
            <h3>Total Pendapatan</h3>
            @if(isset($statistik->total_pendapatan) && $statistik->total_pendapatan > 0)
                <p>Rp {{ number_format($statistik->total_pendapatan, 0, ',', '.') }}</p>
            @else
                <p class="empty-text">Belum ada transaksi pada periode ini</p>
            @endif
        </div>
        <div class="icon">
            <i class="fa fa-dollar-sign"></i>
        </div>
    </div>
</section>

<section class="card chart-card">
    <h3>Grafik Pendapatan</h3>
    <div class="chart-placeholder">
        @if(json_decode($grafikPendapatan_json) == [])
            <span>Belum ada data untuk ditampilkan</span>
        @else
            <span>(Placeholder untuk Grafik Pendapatan)</span>
        @endif
    </div>
</section>

<section class="top5-grid">
    <div class="card top5-card">
        <h3><i class="fa fa-star icon"></i> Top 5 Produk Terlaris</h3>
        <table>
            <tbody>
                @forelse ($topProduk as $produk)
                    <tr>
                        <td>{{ $produk->Nama_Produk }}</td>
                        <td><strong>{{ $produk->total_terjual }}</strong> terjual</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="empty-text">Belum ada produk terjual pada periode ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ======================================= -->
    <!-- INI ADALAH BLOK KODE YANG DIPERBAIKI -->
    <!-- ======================================= -->
    <div class="card top5-card">
        <h3><i class="fa fa-gem icon"></i> Top 5 Member Loyalitas</h3>
        <table>
            <tbody>
                @forelse ($topPelanggan as $pelanggan)
                    <tr>
                        <td>
                            <a href="{{ route('pelanggan.edit', $pelanggan->ID_Pelanggan) }}">
                                {{ $pelanggan->Nama_Pelanggan }}
                            </a>
                        </td>
                        <td><strong>{{ $pelanggan->total_pembelian }}</strong> pembelian</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="empty-text">Belum ada data member pada periode ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section> <!-- <-- Tag </section> yang benar ada di sini -->
@endsection