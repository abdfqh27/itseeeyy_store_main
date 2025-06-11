<!DOCTYPE html>
<html style="margin:0; padding:0; height:100%;">

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        html,
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            border: 0;
            position: relative;
            min-height: 100%;
        }

        .header {
            background-color: #ec4899;
            padding: 10px 30px;
            margin: 0;
            border-bottom-left-radius: 300px;
            color: white;
            box-sizing: border-box;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            padding: 0;
            border: none;
            vertical-align: middle;
        }

        .header-logo-cell img {
            width: 200px;
        }

        .header-text {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
        }

        .content {
            padding: 25px 80px 120px 80px;
            position: relative;
        }

        .header-text h1 {
            font-size: 24px;
            margin: 0 0 5px 0;
            font-weight: bold;
        }

        .header-text p {
            font-size: 16px;
            margin: 0;
        }

        .report-header {
            margin-top: 15px;
            margin-bottom: 25px;
            border: none;
            width: 100%;
        }

        .report-header td {
            padding: 0;
            vertical-align: top;
            border: none;
        }

        .report-header-left {
            width: 60%;
        }

        .report-header-right {
            width: 40%;
            text-align: right;
        }

        .report-label {
            font-size: 16px;
            color: #666;
        }

        .report-value {
            font-size: 20px;
            font-weight: bold;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }

        th {
            background-color: #fdf2f8;
            padding: 12px;
            text-align: left;
            color: #db2777;
            font-size: 15px;
        }

        td {
            padding: 12px;
            border: 0px solid #fbcfe8;
            vertical-align: top;
            font-size: 14px;
        }

        .summary-table th {
            text-align: right;
        }

        .stock-in {
            color: #16a34a;
        }

        .stock-out {
            color: #dc2626;
        }

        .info-penting {
            margin: 35px 0;
            color: #666;
            font-size: 13px;
            border-radius: 5px;
            padding: 15px;
            background-color: #fdf2f8;
            border-left: 4px solid #ec4899;
        }

        .info-penting strong {
            font-size: 17px;
            font-weight: semibold;
            display: block;
            margin-bottom: 5px;
        }

        .footer-wrapper {
            position: absolute;
            bottom: 25px;
            left: 0;
            right: 0;
            padding: 0 40px;
        }

        .footer-table {
            width: 100%;
            font-size: 12px;
            color: #666;
            border-collapse: collapse;
        }

        .footer-left {
            vertical-align: middle;
            width: 70%;
        }

        .footer-logo-text {
            vertical-align: middle;
        }

        .footer-logo-text img {
            vertical-align: middle;
            margin-right: 8px;
            width: 50px;
            height: 50px;
        }

        .footer-right {
            text-align: right;
            vertical-align: middle;
            font-weight: bold;
            width: 30%;
        }

        h3 {
            font-size: 18px;
            color: #db2777;
            margin-top: 30px;
        }

        h4 {
            font-size: 16px;
            color: #666;
            border-bottom: 1px solid #fbcfe8;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-logo-cell">
                    <img src="{{ public_path('storage/itseeystore-logo.jpg')}}" alt="Logo" style="display: block; margin-left: 40px;">
                </td>
                <td class="header-text">
                    Laporan Pencatatan Stok | Laporan Transaksi
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <table class="report-header">
            <tr>
                <td class="report-header-left">
                    <div class="report-label">Menampilkan laporan Itseey Store dari</div>
                    <div class="report-value">{{ $fromDate ?? $groupedData->keys()->first() }} - {{ $toDate ?? $groupedData->keys()->last() }}</div>
                </td>
                <td class="report-header-right">
                    <div class="report-label">Stok terakhir per {{ now()->format('d M Y') }}</div>
                    <div class="report-value">
                        @php
                        $totalIn = $groupedData->flatten()->where('type', 'in')->sum('quantity');
                        $totalOut = $groupedData->flatten()->where('type', 'out')->sum('quantity');
                        $totalNet = $totalIn - $totalOut;
                        @endphp
                        {{ $totalNet >= 0 ? '+' : '' }}{{ $totalNet }}
                    </div>
                </td>
            </tr>
        </table>

        @if($groupedData->isEmpty())
        <p>Tidak ada data untuk periode yang dipilih.</p>
        @else
        <table>
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Stok Masuk (Total)</th>
                    <th>Stok Keluar (Total)</th>
                    <th>Perubahan Bersih</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupedData as $period => $movements)
                @php
                $stockIn = $movements->where('type', 'in')->sum('quantity');
                $stockOut = $movements->where('type', 'out')->sum('quantity');
                $netChange = $stockIn - $stockOut;
                @endphp
                <tr>
                    <td>{{ $period }}</td>
                    <td class="stock-in">+{{ $stockIn }}</td>
                    <td class="stock-out">-{{ $stockOut }}</td>
                    <td style="font-weight: bold; {{ $netChange >= 0 ? 'color: #16a34a;' : 'color: #dc2626;' }}">
                        {{ $netChange >= 0 ? '+' : '' }}{{ $netChange }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td><b>Total</b></td>
                    <td class="stock-in" style="font-weight: bold;">
                        +{{ $groupedData->flatten()->where('type', 'in')->sum('quantity') }}
                    </td>
                    <td class="stock-out" style="font-weight: bold;">
                        -{{ $groupedData->flatten()->where('type', 'out')->sum('quantity') }}
                    </td>
                    <td style="font-weight: bold;
                        {{ ($groupedData->flatten()->where('type', 'in')->sum('quantity') - $groupedData->flatten()->where('type', 'out')->sum('quantity')) >= 0 ? 'color: #16a34a;' : 'color: #dc2626;' }}">
                        @php
                        $totalIn = $groupedData->flatten()->where('type', 'in')->sum('quantity');
                        $totalOut = $groupedData->flatten()->where('type', 'out')->sum('quantity');
                        $totalNet = $totalIn - $totalOut;
                        @endphp
                        {{ $totalNet >= 0 ? '+' : '' }}{{ $totalNet }}
                    </td>
                </tr>
            </tfoot>
        </table>
        <h3>Rincian Pencatatan Stok</h3>
        @foreach($groupedData as $period => $movements)
        <h4>{{ $period }}</h4>
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Kuantitas</th>
                    <th>Tanggal</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movements as $movement)
                <tr>
                    <td>{{ $movement->product->name }}</td>
                    <td>{{ $movement->product->category->name }}</td>
                    <td>{{ $movement->type === 'in' ? 'Stok Masuk' : 'Stok Keluar' }}</td>
                    <td>{{ $movement->quantity }}</td>
                    <td>
                        <div style="line-height: 1.2;">
                            <div style="font-weight: medium;">{{ $movement->created_at->format('d M Y') }}</div>
                            <div style="color: #666; font-size: 11px;">{{ $movement->created_at->format('H.i') }}</div>
                        </div>
                    </td>
                    <td>{{ $movement->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
        @endif

        <div class="info-penting">
            <strong>Info Penting</strong>
            <p>Dokumen ini merupakan daftar Riwayat Transaksi Itseey Store dan dihasilkan berdasarkan transaksi yang dilakukan pada periode yang tercantum dalam laporan. Transaksi dapat ditampilkan berdasarkan hasil pencarian dan filter yang diterapkan oleh pengguna.</p>
        </div>

        <div class="footer-wrapper">
            <table class="footer-table">
                <tr>
                    <td class="footer-left">
                        <span class="footer-logo-text">
                            <img src="{{ public_path('storage/itseeystore-1080.jpg')}}" alt="Logo">
                            Sistem Manajemen Skincare &copy; 2025 | Semua Hak Dilindungi
                        </span>
                    </td>
                    <td class="footer-right">
                        www.itseeystore.my.id
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>