<!DOCTYPE html>
<html>
<head>
    <title>Laporan Dashboard SIDAC</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #0d6efd; font-size: 24px; }
        .header p { margin: 2px 0; font-size: 10px; color: #555; }
        
        .info-section { margin-bottom: 20px; width: 100%; }
        .info-table td { padding: 3px; }
        .label { font-weight: bold; width: 120px; }

        /* Stats Cards Style (Simulated with Table) */
        .stats-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .stats-table td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: center; 
            width: 33%; 
            background-color: #f8f9fa;
        }
        .stat-value { font-size: 16px; font-weight: bold; color: #0d6efd; display: block; margin-top: 5px; }
        .stat-label { font-size: 10px; text-transform: uppercase; color: #666; }

        /* Section Titles */
        h3 { border-bottom: 1px solid #ccc; padding-bottom: 5px; color: #444; margin-top: 20px; }

        /* Tables */
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .data-table th { background-color: #0d6efd; color: white; font-size: 11px; }
        .data-table td { font-size: 11px; }
        .text-right { text-align: right; }
        
        /* Charts Layout */
        .charts-container { width: 100%; margin-bottom: 20px; text-align: center; }
        .chart-img { max-width: 100%; height: auto; border: 1px solid #eee; padding: 5px; }
    </style>
</head>
<body>

    <!-- 1. HEADER -->
    <div class="header">
        <h1>Laporan Dashboard SIDAC</h1>
        <p>Sistem Informasi Data Coffee Shop</p>
    </div>

    <!-- 2. INFO FILTER -->
    <table class="info-section info-table">
        <tr>
            <td class="label">Dicetak Oleh:</td>
            <td>{{ $namaUser }} ({{ $roleUser }})</td>
            <td class="label">Tanggal Cetak:</td>
            <td>{{ now()->format('d F Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Periode Data:</td>
            <td>
                {{ \Carbon\Carbon::parse($tgl_mulai)->format('d M Y') }} s/d 
                {{ \Carbon\Carbon::parse($tgl_selesai)->format('d M Y') }}
            </td>
            <td class="label">Filter Produk:</td>
            <td>{{ $produkNama ?? 'Semua Produk' }}</td>
        </tr>
    </table>

    <!-- 3. STATISTIK RINGKAS -->
    <table class="stats-table">
        <tr>
            <td>
                <span class="stat-label">Total Transaksi</span>
                <span class="stat-value">{{ number_format($statistik->total_transaksi ?? 0) }}</span>
            </td>
            <td>
                <span class="stat-label">Total Pendapatan</span>
                <span class="stat-value">Rp {{ number_format($statistik->total_pendapatan ?? 0, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="stat-label">Rata-rata Order</span>
                @php
                    $avgOrder = ($statistik->total_transaksi > 0) ? ($statistik->total_pendapatan / $statistik->total_transaksi) : 0;
                @endphp
                <span class="stat-value">Rp {{ number_format($avgOrder, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <!-- 4. GRAFIK (GAMBAR HASIL TANGKAPAN JS) -->
    <!-- Menggunakan tabel untuk layout grafik berdampingan (jika muat) atau atas-bawah -->
    <table width="100%">
        <tr>
            <!-- Grafik Tren -->
            <td width="60%" valign="top" style="padding-right: 10px;">
                <h3>Tren Pendapatan Harian</h3>
                @if(isset($chartImagePath) && file_exists($chartImagePath))
                    <img src="{{ $chartImagePath }}" class="chart-img" style="width: 100%;">
                @else
                    <p style="color: #999; text-align: center; padding: 20px; border: 1px dashed #ccc;">Grafik Tren tidak tersedia</p>
                @endif
            </td>
            
            <!-- Grafik Donut (Menu) -->
            <td width="40%" valign="top">
                <h3>5 Menu Terlaris</h3>
                @if(isset($chartProdukPath) && file_exists($chartProdukPath))
                    <img src="{{ $chartProdukPath }}" class="chart-img" style="width: 100%;">
                @else
                    <p style="color: #999; text-align: center; padding: 20px; border: 1px dashed #ccc;">Grafik Menu tidak tersedia</p>
                @endif
            </td>
        </tr>
    </table>

    <br>

    <!-- 5. TABEL DATA RINCI -->
    
    <!-- Tabel A: Transaksi Terbaru -->
    <h3>A. 5 Transaksi Terbaru</h3>
    <table class="data-table">
        <thead>
            <tr>
                <!-- Saya perlebar kolom ID agar SCCK... muat -->
                <th width="35%">ID Transaksi</th> 
                <th width="20%">Tanggal</th>
                <th width="25%">Kasir</th>
                <th width="20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksiTerbaru as $trx)
            <tr>
                <!-- PERBAIKAN: Menghapus substr() agar ID tampil lengkap -->
                <td>{{ $trx->ID_Transaksi }}</td>
                
                <td>{{ $trx->Tanggal }}</td>
                <td>{{ $trx->Nama_Kasir ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($trx->TotalHarga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            @if(count($transaksiTerbaru) == 0)
                <tr><td colspan="4" style="text-align:center">Tidak ada data</td></tr>
            @endif
        </tbody>
    </table>

    <!-- Tabel B & C Berdampingan -->
    <table width="100%" style="margin-top: 10px;">
        <tr>
            <td width="48%" valign="top" style="border: none; padding: 0; padding-right: 10px;">
                <h3>B. Top 5 Member</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Member</th>
                            <th class="text-right">Frekuensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topPelanggan as $plg)
                        <tr>
                            <td>{{ $plg->Nama_Pelanggan }}</td>
                            <td class="text-right">{{ $plg->Frekuensi_Pembelian }} x</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>

            <td width="4%" style="border: none;"></td> <!-- Spacer -->

            <td width="48%" valign="top" style="border: none; padding: 0;">
                <h3>C. Metode Pembayaran</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Metode</th>
                            <th class="text-right">Penggunaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topMetode as $metode)
                        <tr>
                            <td>{{ $metode->Metode_Pembayaran }}</td>
                            <td class="text-right">{{ $metode->total_usage }} Trx</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>