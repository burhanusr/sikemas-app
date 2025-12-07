<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - {{ $masjid->organization }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }

        .page {
            padding: 20mm 15mm;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 14pt;
            color: #333;
            font-weight: bold;
            margin-bottom: 8px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            background-color: #e0e0e0;
            color: #333;
            padding: 8px 6px;
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
            border: 1px solid #999;
        }

        td {
            padding: 6px;
            border: 1px solid #999;
            font-size: 9pt;
        }

        /* Section Headers */
        .section-header {
            background-color: #f5f5f5 !important;
            font-weight: bold;
            color: #333;
            padding: 6px !important;
            font-size: 9pt;
            border: 1px solid #999 !important;
        }

        /* Balance Rows */
        .balance-row {
            background-color: #f9f9f9 !important;
            font-weight: bold;
        }

        /* Total Rows */
        .total-row {
            background-color: #e8e8e8 !important;
            font-weight: bold;
            font-size: 9pt;
        }

        /* Number Formatting */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            font-size: 8pt;
            color: #666;
            text-align: center;
        }

        /* Page Break Control */
        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <h1>Laporan Aktivitas Keuangan {{ $masjid->organization }}</h1>
            <h1>Bulan {{ \Carbon\Carbon::create($year, $month)->locale('id')->translatedFormat('F Y') }}</h1>
        </div>

        <!-- Main Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 30%;">Transaksi</th>
                    <th style="width: 30%;">Keterangan</th>
                    <th style="width: 11%;">Nominal</th>
                    <th style="width: 12%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $runningBalance = $previousBalance;

                    // Combine and sort all transactions by date
                    $allTransactions = collect();

                    foreach ($pemasukan as $item) {
                        $allTransactions->push([
                            'tanggal' => $item->tanggal,
                            'jenis' => 'pemasukan',
                            'keterangan' => $item->keterangan,
                            'kode_akun' => $item->kodeakun->nama_akun ?? '-',
                            'nominal' => $item->nominal,
                            'id' => $item->id,
                        ]);
                    }

                    foreach ($pengeluaran as $item) {
                        $allTransactions->push([
                            'tanggal' => $item->tanggal,
                            'jenis' => 'pengeluaran',
                            'keterangan' => $item->keterangan,
                            'kode_akun' => $item->kodeakun->nama_akun ?? '-',
                            'nominal' => $item->nominal,
                            'id' => $item->id,
                        ]);
                    }

                    $allTransactions = $allTransactions->sortBy([['tanggal', 'asc'], ['id', 'asc']]);

                    $totalPemasukan = 0;
                    $totalPengeluaran = 0;

                    // Get previous month
                    $previousMonthDate = \Carbon\Carbon::create($year, $month)->subMonth();
                @endphp

                <!-- Saldo Section -->
                <tr class="section-header">
                    <td colspan="6">Saldo Masjid</td>
                </tr>

                <!-- Opening Balance -->
                <tr class="balance-row">
                    <td class="text-center">{{ $no++ }}</td>
                    <td colspan="4">Saldo per {{ $previousMonthDate->locale('id')->translatedFormat('F Y') }}</td>
                    <td class="text-right">{{ number_format($runningBalance, 0, ',', '.') }}</td>
                </tr>

                <!-- Pemasukan Section -->
                <tr class="section-header">
                    <td colspan="6">Pemasukan</td>
                </tr>

                @foreach ($allTransactions->where('jenis', 'pemasukan') as $item)
                    @php
                        $runningBalance += $item['nominal'];
                        $totalPemasukan += $item['nominal'];
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}</td>
                        <td>{{ $item['kode_akun'] }}</td>
                        <td>{{ $item['keterangan'] ?: '-' }}</td>
                        <td class="text-right">{{ number_format($item['nominal'], 0, ',', '.') }}</td>
                        <td class="text-right"></td>
                    </tr>
                @endforeach

                @if ($totalPemasukan > 0)
                    <tr class="total-row">
                        <td colspan="5" class="text-left">Total Pemasukan</td>
                        <td class="text-right">{{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                    </tr>
                @endif

                <!-- Pengeluaran Section -->
                <tr class="section-header">
                    <td colspan="6">Pengeluaran</td>
                </tr>

                @foreach ($allTransactions->where('jenis', 'pengeluaran') as $item)
                    @php
                        $runningBalance -= $item['nominal'];
                        $totalPengeluaran += $item['nominal'];
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}</td>
                        <td>{{ $item['kode_akun'] }}</td>
                        <td>{{ $item['keterangan'] ?: '-' }}</td>
                        <td class="text-right">{{ number_format($item['nominal'], 0, ',', '.') }}</td>
                        <td class="text-right"></td>
                    </tr>
                @endforeach

                @if ($totalPengeluaran > 0)
                    <tr class="total-row">
                        <td colspan="5" class="text-left">Total Pengeluaran</td>
                        <td class="text-right">{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                    </tr>
                @endif

                <!-- Closing Balance -->
                <tr class="total-row">
                    <td colspan="5" class="text-left"><strong>Saldo Akhir</strong></td>
                    <td class="text-right"><strong>{{ number_format($runningBalance, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    {{-- <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y H:i') }}
    </div> --}}
</body>

</html>
