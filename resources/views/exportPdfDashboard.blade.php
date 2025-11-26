<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            margin-bottom: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        td,
        th {
            padding: 6px;
            border-bottom: 1px solid #ccc;
        }
    </style>
</head>

<body>

    <h2>Dashboard SIDAC</h2>

    <p>Diunduh oleh: <strong>{{ $namaUser }}</strong></p>

    <p>Periode:
        <strong>{{ $tgl_mulai ? $tgl_mulai : '-' }}</strong> 
        s/d 
        <strong>{{ $tgl_selesai ? $tgl_selesai : '-' }}</strong>
    </p>

    <p>
        Produk:
        <strong>
            @if($produk_filter)
                {{ $produkNama }}
            @else
                Semua Produk
            @endif
        </strong>
    </p>

    <hr>

    <h3>Statistik</h3>
    <table>
        <tr>
            <td><strong>Total Transaksi</strong></td>
            <td>
                @if ($statistik->total_transaksi > 0)
                    {{ $statistik->total_transaksi }}
                @else
                    Tidak ada transaksi pada periode ini
                @endif
            </td>
        </tr>

        <tr>
            <td><strong>Total Pendapatan</strong></td>
            <td>
                @if ($statistik->total_pendapatan > 0)
                    Rp {{ number_format($statistik->total_pendapatan, 0, ',', '.') }}
                @else
                    Tidak ada pendapatan pada periode ini
                @endif
            </td>
        </tr>
    </table>

    <hr>

    <h3>Grafik Transaksi</h3>

    @if ($chartImagePath && file_exists($chartImagePath))
    <img src="file://{{ $chartImagePath }}" width="600">
    @else
        <p><i>Grafik tidak tersedia</i></p>
    @endif


    <hr>

    <h3>Top 5 Produk Terlaris</h3>
    <table>
        @forelse ($topProduk as $p)
            <tr>
                <td>{{ $p->Nama_Produk }}</td>
                <td>{{ $p->total_terjual }} terjual</td>
            </tr>
        @empty
            <tr>
                <td colspan="2"><i>Tidak ada produk terjual pada periode ini</i></td>
            </tr>
        @endforelse
    </table>

    <hr>

    <h3>Top 5 Member Loyalitas</h3>
    <table>
        @forelse ($topPelanggan as $c)
            <tr>
                <td>{{ $c->Nama_Pelanggan }}</td>
                <td>{{ $c->Frekuensi_Pembelian }} pembelian</td>
            </tr>
        @empty
            <tr>
                <td colspan="2"><i>Tidak ada data member pada periode ini</i></td>
            </tr>
        @endforelse
    </table>

</body>

</html>
