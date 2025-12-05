<table>
    <tr>
        <td>LAPORAN AKTIVITAS KEUANGAN {{ strtoupper($masjid->organization) }}</td>
    </tr>
    <tr>
        <td>BULAN {{ strtoupper(\Carbon\Carbon::create($year, $month)->translatedFormat('F Y')) }}</td>
    </tr>
    <tr></tr>

    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Kode Akun</th>
        <th>Keterangan</th>
        <th>Nominal</th>
        <th>Jumlah</th>
    </tr>

    {{-- Saldo Masjid (Previous Balance) --}}
    <tr>
        <td>Saldo Masjid</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{ $previousBalance }}</td>
    </tr>

    {{-- Total Saldo per Previous Month --}}
    <tr>
        <td>Total Saldo per {{ \Carbon\Carbon::create($year, $month)->subMonth()->translatedFormat('F Y') }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{ $previousBalance }}</td>
    </tr>

    {{-- Pemasukan Section --}}
    <tr>
        <td>Pemasukan</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    @php
        $no = 1;
        $totalPemasukan = 0;
    @endphp

    @foreach ($pemasukan as $item)
        @php
            $totalPemasukan += $item->nominal;
        @endphp
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('n/j/Y') }}</td>
            <td>{{ $item->kodeakun->nama_akun ?? '-' }}</td>
            <td>{{ $item->keterangan }}</td>
            <td>{{ $item->nominal }}</td>
            <td></td>
        </tr>
    @endforeach

    <tr>
        <td>Total Pemasukan</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{ $totalPemasukan }}</td>
    </tr>

    {{-- Pengeluaran Section --}}
    <tr>
        <td>Pengeluaran</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    @php
        $no = 1;
        $totalPengeluaran = 0;
    @endphp

    @foreach ($pengeluaran as $item)
        @php
            $totalPengeluaran += $item->nominal;
        @endphp
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('n/j/Y') }}</td>
            <td>{{ $item->kodeakun->nama_akun ?? '-' }}</td>
            <td>{{ $item->keterangan }}</td>
            <td>{{ $item->nominal }}</td>
            <td></td>
        </tr>
    @endforeach

    <tr>
        <td>Total Pengeluaran</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{ $totalPengeluaran }}</td>
    </tr>

    {{-- Saldo Akhir --}}
    @php
        $saldoAkhir = $previousBalance + $totalPemasukan - $totalPengeluaran;
    @endphp
    <tr>
        <td>Saldo Akhir</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{ $saldoAkhir }}</td>
    </tr>
</table>
