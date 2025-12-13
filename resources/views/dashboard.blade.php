@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        /* CSS KHUSUS DASHBOARD */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        /* Header Styles */
        .sub-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .sub-header .title { display: flex; align-items: center; gap: 10px; }
        .sub-header .title .icon { background-color: #0d6efd; color: white; padding: 10px; border-radius: 8px; font-size: 1.2em; }
        .sub-header h1 { margin: 0; font-size: 1.5em; }
        .sub-header p { margin: 0; color: #555; }
        .btn-export { background: linear-gradient(135deg, #e65c00, #ff9900); color: #fff; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; border: none; cursor: pointer; font-size: 0.9em; }

        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); margin-bottom: 20px; }

        /* Filter Styles */
        .filter-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; align-items: end; }
        .filter-group { display: flex; flex-direction: column; position: relative; }
        .filter-group label { font-size: 0.8em; color: #555; margin-bottom: 5px; }
        .filter-group input, .filter-group select { padding: 8px 15px; border: 1px solid #ccc; border-radius: 4px; width: 100%; box-sizing: border-box; }
        .filter-group .input-icon { position: absolute; right: 12px; bottom: 12px; color: #888; pointer-events: none; font-size: 0.8em; z-index: 2; }
        
        /* [PERBAIKAN] CSS Button Filter agar rapi */
        .btn-filter { 
            background: #0d6efd; 
            color: #fff; 
            border: none; 
            padding: 10px; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 0.9em; 
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 38px; /* Tinggi fix agar sejajar input */
        }
        .filter-buttons { 
            display: flex; 
            gap: 10px; 
            /* margin-top dihapus agar sejajar dengan align-items: end grid */
        }
        /* Memaksa kedua tombol berbagi lebar sama rata */
        .filter-buttons .btn-filter, 
        .filter-buttons a.btn-filter {
            flex: 1; 
            text-decoration: none;
            color: white;
        }
        
        /* Quick Filter Buttons */
        .quick-actions { display: flex; gap: 10px; margin-bottom: 15px; margin-top: 15px; flex-wrap: wrap; }
        .btn-quick { padding: 6px 15px; border: 1px solid #0d6efd; color: #0d6efd; border-radius: 20px; font-size: 0.85em; text-decoration: none; transition: all 0.3s; background: transparent; font-weight: 500; }
        .btn-quick:hover, .btn-quick.active { background-color: #0d6efd; color: white; box-shadow: 0 2px 5px rgba(13, 110, 253, 0.3); }

        .filter-group select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: none;
        }

        /* Stats Grid (3 Kolom) */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px; margin-bottom: 20px; }
        .stat-card { display: flex; justify-content: space-between; align-items: center; padding: 20px; border-radius: 8px; color: white; }
        .stat-card-blue { background: linear-gradient(135deg, #0048b5, #007bff); }
        .stat-card-orange { background: linear-gradient(135deg, #e65c00, #ff9900); }
        .stat-card-green { background: linear-gradient(135deg, #198754, #20c997); }
        .stat-card h3 { margin: 0; font-size: 1.1em; font-weight: 500; }
        .stat-card p { margin: 5px 0 0 0; font-size: 1.5em; font-weight: bold; }
        .stat-card .icon { font-size: 2em; opacity: 0.7; }

        /* [LAYOUT BARU] Charts Row (Grid 2 Kolom: Kiri Besar, Kanan Kecil) */
        .charts-row { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px; }
        .chart-placeholder { width: 100%; height: 300px; } 
        .donut-placeholder { width: 100%; height: 260px; display: flex; justify-content: center; align-items: center; }

        /* [LAYOUT BARU] Tables Row (Grid 3 Kolom Sejajar) */
        .tables-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        
        /* Table Styles */
        .content-card h3 { margin-top: 0; font-size: 1.1em; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #f0f0f0; padding-bottom: 10px; margin-bottom: 15px; }
        .content-card table { width: 100%; border-collapse: collapse; }
        .content-card th, .content-card td { padding: 10px 5px; border-bottom: 1px solid #f0f0f0; font-size: 0.85em; }
        .content-card th { text-align: left; color: #555; }
        .content-card .amount { text-align: right; font-weight: bold; color: #198754; }
        .content-card .empty-text { color: #888; font-style: italic; text-align: center; padding: 15px; }

        /* Responsive */
        @media (max-width: 1200px) { 
            .charts-row { grid-template-columns: 1fr; }
            .tables-row { grid-template-columns: 1fr; } 
            .stats-grid { grid-template-columns: 1fr; }
            .filter-grid { grid-template-columns: 1fr; } /* Responsif filter di layar kecil */
        }
    </style>

    <!-- HEADER & EXPORT -->
    <section class="sub-header">
        <div class="title">
            <span class="icon"><i class="fa fa-chart-bar"></i></span>
            <div>
                <h1>Dashboard SIDAC</h1>
                <p>Monitor Performa Bisnis Anda (Welcome, <strong>{{ $namaUser }}</strong>!)</p>
            </div>
        </div>

        @if ($roleUser == 'Manajer Operasional')
            <form method="POST" action="{{ route('dashboard.export.pdf') }}" id="exportForm" target="_blank">
                @csrf
                <input type="hidden" name="chart_image" id="chart_image">
                <input type="hidden" name="chart_produk_image" id="chart_produk_image">
                <input type="hidden" name="tgl_mulai" value="{{ $tgl_mulai }}">
                <input type="hidden" name="tgl_selesai" value="{{ $tgl_selesai }}">
                <input type="hidden" name="produk_id" value="{{ $produk_filter }}">
                <button type="submit" class="btn-export"><i class="fa fa-file-pdf"></i> Ekspor PDF</button>
            </form>
        @endif
    </section>

    <!-- FILTER SECTION -->
    <section class="filter-card card">
        <h2>Filter Dashboard</h2>
        <div class="quick-actions">
            <a href="{{ route('dashboard', ['tgl_mulai' => date('Y-m-d'), 'tgl_selesai' => date('Y-m-d')]) }}" class="btn-quick {{ request('tgl_mulai') == date('Y-m-d') ? 'active' : '' }}">Hari Ini</a>
            <a href="{{ route('dashboard', ['tgl_mulai' => date('Y-m-d', strtotime('-6 days')), 'tgl_selesai' => date('Y-m-d')]) }}" class="btn-quick {{ request('tgl_mulai') == date('Y-m-d', strtotime('-6 days')) ? 'active' : '' }}">7 Hari Terakhir</a>
            <a href="{{ route('dashboard', ['tgl_mulai' => date('Y-m-01'), 'tgl_selesai' => date('Y-m-t')]) }}" class="btn-quick {{ request('tgl_mulai') == date('Y-m-01') ? 'active' : '' }}">Bulan Ini</a>
            <a href="{{ route('dashboard', ['tgl_mulai' => date('Y-m-01', strtotime('last month')), 'tgl_selesai' => date('Y-m-t', strtotime('last month'))]) }}" class="btn-quick {{ request('tgl_mulai') == date('Y-m-01', strtotime('last month')) ? 'active' : '' }}">Bulan Lalu</a>
        </div>
        <form method="GET" action="{{ route('dashboard') }}">
            <div class="filter-grid">
                <div class="filter-group"><label>Tanggal Mulai</label><input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}"></div>
                <div class="filter-group"><label>Tanggal Selesai</label><input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}"></div>
                <div class="filter-group">
                    <label>Produk</label>
                    <select name="produk_id">
                        <option value="">Semua Produk</option>
                        @foreach ($produks_list as $produk)
                            <option value="{{ $produk->ID_Produk }}" {{ ($produk_filter ?? '') == $produk->ID_Produk ? 'selected' : '' }}>{{ $produk->Nama_Produk }}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-chevron-down input-icon"></i>
                </div>
                <div class="filter-buttons">
                    <button type="submit" class="btn-filter"><i class="fa fa-search"></i> Terapkan</button>
                    <a href="{{ route('dashboard') }}" class="btn-filter" style="background:#6c757d !important;"><i class="fa fa-undo"></i> Reset</a>
                </div>
            </div>
        </form>
    </section>

    <!-- STATISTIK CARDS -->
    <section class="stats-grid">
        <div class="stat-card stat-card-blue">
            <div><h3>Total Transaksi</h3><p>{{ number_format($statistik->total_transaksi ?? 0) }}</p></div><div class="icon"><i class="fa fa-shopping-cart"></i></div>
        </div>
        <div class="stat-card stat-card-orange">
            <div><h3>Total Pendapatan</h3><p>Rp {{ number_format($statistik->total_pendapatan ?? 0, 0, ',', '.') }}</p></div><div class="icon"><i class="fa fa-dollar-sign"></i></div>
        </div>
        <div class="stat-card stat-card-green">
            <div><h3>Rata-rata Order</h3><p>Rp {{ number_format(($statistik->total_transaksi > 0) ? ($statistik->total_pendapatan / $statistik->total_transaksi) : 0, 0, ',', '.') }}</p></div><div class="icon"><i class="fa fa-chart-pie"></i></div>
        </div>
    </section>

    <!-- CHARTS ROW (2 Grafik Sejajar) -->
    <section class="charts-row">
        <!-- 1. Grafik Tren (Kiri) -->
        <div class="card chart-card">
            <h3 style="margin-top:0; font-size:1.2em; border-bottom:1px solid #eee; padding-bottom:10px;">
                <i class="fa fa-chart-area" style="color:#0d6efd; margin-right:8px;"></i> Tren jumlah transaksi
            </h3>
            <div class="chart-placeholder">
                <canvas id="chartTransaksi"></canvas>
            </div>
        </div>

        <!-- 2. Grafik Donut Menu (Kanan) -->
        <div class="card content-card">
            <h3><i class="fa fa-utensils" style="color:#dc3545;"></i> 5 Menu Terlaris</h3>
            <div class="donut-placeholder">
                @if(isset($chartProdukLabel) && count($chartProdukLabel) > 0)
                    <canvas id="chartProduk"></canvas>
                @else
                    <p class="empty-text">Belum ada data penjualan</p>
                @endif
            </div>
        </div>
    </section>

    <!-- TABLES ROW (3 Tabel Sejajar) -->
    <section class="tables-row">
        
        <!-- Tabel 1: Transaksi Terbaru
        <div class="card content-card">
            <h3><i class="fa fa-history" style="color:#ffc107;"></i> Transaksi Terbaru</h3>
            <table>
                <thead><tr><th>ID</th><th>Kasir</th><th style="text-align:right;">Total</th></tr></thead>
                <tbody>
                    @forelse($transaksiTerbaru as $trx)
                    <tr>
                        <td style="color:#777;">#{{ substr($trx->ID_Transaksi, -4) }}</td>
                        <td>{{ $trx->Nama_Kasir ?? 'Kasir' }}</td>
                        <td class="amount">Rp {{ number_format($trx->TotalHarga, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="empty-text">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div> -->

        <!-- KOLOM 2: Tabel Transaksi Terbaru -->
        <div class="card content-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #f0f0f0; padding-bottom: 10px;">
                <h3 style="margin: 0; border: none; padding: 0;"><i class="fa fa-history" style="color:#ffc107;"></i> Transaksi Terbaru</h3>
                
                <!-- TOMBOL VIEW ALL (Mengirim filter tanggal ke halaman transaksi) -->
                <a href="{{ route('transaksi.index', ['tgl_mulai' => $tgl_mulai, 'tgl_selesai' => $tgl_selesai]) }}" 
                   style="font-size: 0.85em; text-decoration: none; color: #0d6efd; font-weight: 600;">
                    Lihat Semua <i class="fa fa-arrow-right"></i>
                </a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kasir</th> 
                        <th style="text-align:right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru as $trx)
                    <tr>
                        <td style="color:#777; font-size:0.85em;">#{{ substr($trx->ID_Transaksi, -4) }}</td>
                        <td>{{ $trx->Nama_Kasir ?? 'Kasir' }}</td>
                        <td class="amount">Rp {{ number_format($trx->TotalHarga, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="empty-text">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tabel 2: Top Member -->
        <div class="card content-card">
            <h3><i class="fa fa-gem icon"></i> Top 5 Member</h3>
            <table>
                <tbody>
                    @forelse ($topPelanggan as $pelanggan)
                    <tr>
                        <td><a href="{{ route('pelanggan.edit', $pelanggan->ID_Pelanggan) }}" style="text-decoration:none; color:#333;">{{ $pelanggan->Nama_Pelanggan }}</a></td>
                        <td style="text-align:right;"><strong>{{ $pelanggan->Frekuensi_Pembelian }}</strong> x</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="empty-text">Belum ada member</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tabel 3: Metode Pembayaran (PENGGANTI KASIR) -->
        <div class="card content-card">
            <h3><i class="fa fa-wallet" style="color:#20c997;"></i> Metode Bayar</h3>
            <table>
                <tbody>
                    @if(isset($topMetode) && count($topMetode) > 0)
                        @foreach ($topMetode as $item)
                        <tr>
                            <td>{{ $item->Metode_Pembayaran }}</td>
                            <td style="text-align:right;"><strong>{{ $item->total_usage }}</strong> Trx</td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="2" class="empty-text">Belum ada data</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

    </section>

    <div 
        id="grafik-data"
        data-grafik='@json($grafikTransaksi)'>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // ===============================
    // 1. LINE CHART
    // ===============================
        const grafikData = JSON.parse(
            document.getElementById('grafik-data').dataset.grafik
        );
        const ctxLine = document.getElementById('chartTransaksi');

        if (ctxLine && grafikData.length > 0) {
            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: grafikData.map(d => d.tanggal),
                    datasets: [{
                        label: 'Jumlah Transaksi',
                        data: grafikData.map(d => d.total_transaksi),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true },
                        x: { }
                    }
                }
            });
        }

        // ===============================
        // 2. DONUT CHART PRODUK
        // ===============================
        const chartProdukLabel = JSON.parse('{!! json_encode($chartProdukLabel ?? []) !!}');
        const chartProdukData  = JSON.parse('{!! json_encode($chartProdukData ?? []) !!}');

        const ctxProduk = document.getElementById('chartProduk');

        if (ctxProduk && chartProdukLabel.length > 0) {
            new Chart(ctxProduk, {
                type: 'doughnut',
                data: {
                    labels: chartProdukLabel,
                    datasets: [{
                        data: chartProdukData,
                        backgroundColor: [
                            '#0d6efd', '#ffc107', '#198754',
                            '#dc3545', '#6c757d', '#0dcaf0'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 10, font: { size: 9 } }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        // ===============================
        // 3. EXPORT PDF HANDLER
        // ===============================
        document.addEventListener("DOMContentLoaded", () => {
            const exportForm = document.getElementById("exportForm");

            if (exportForm) {
                exportForm.addEventListener("submit", () => {
                    if (ctxLine) {
                        document.getElementById("chart_image").value =
                            ctxLine.toDataURL("image/png");
                    }

                    if (ctxProduk) {
                        document.getElementById("chart_produk_image").value =
                            ctxProduk.toDataURL("image/png");
                    }
                });
            }
        });
    </script>

@endsection