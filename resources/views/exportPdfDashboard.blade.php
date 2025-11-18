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
        <strong>{{ $tgl_mulai ?? '-' }}</strong> s/d
        <strong>{{ $tgl_selesai ?? '-' }}</strong>
    </p>

    <hr>

    <h3>Statistik</h3>
    <table>
        <tr>
            <td><strong>Total Transaksi</strong></td>
            <td>{{ $statistik->total_transaksi }}</td>
        </tr>
        <tr>
            <td><strong>Total Pendapatan</strong></td>
            <td>Rp {{ number_format($statistik->total_pendapatan, 0, ',', '.') }}</td>
        </tr>
    </table>

    <hr>

    <h3>Grafik Transaksi</h3>
    @if ($chartImagePath)
        <img src="{{ public_path($chartImagePath) }}" width="600">
    @else
        <p><i>Grafik tidak tersedia</i></p>
    @endif

    <hr>

    <h3>Top 5 Produk Terlaris</h3>
    <table>
        @foreach ($topProduk as $p)
            <tr>
                <td>{{ $p->Nama_Produk }}</td>
                <td>{{ $p->total_terjual }} terjual</td>
            </tr>
        @endforeach
    </table>

    <hr>

    <h3>Top 5 Member Loyalitas</h3>
    <table>
        @foreach ($topPelanggan as $c)
            <tr>
                <td>{{ $c->Nama_Pelanggan }}</td>
                <td>{{ $c->total_pembelian }} pembelian</td>
            </tr>
        @endforeach
    </table>

</body>

</html>
