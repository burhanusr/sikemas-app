<table>
    <tr>
        <td colspan="6">LAPORAN AKTIVITAS KEUANGAN {{ strtoupper($masjid->organization) }}</td>
    </tr>
    <tr>
        <td colspan="6">BULAN
            {{ strtoupper(\Carbon\Carbon::create($year, $month)->locale('id')->translatedFormat('F Y')) }}</td>
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
        <td colspan="6">Saldo Masjid</td>
        {{-- <td>{{ $previousBalance }}</td> --}}
    </tr>

    {{-- Total Saldo per Previous Month --}}
    <tr>
        <td colspan="5">Total Saldo per
            {{ \Carbon\Carbon::create($year, $month)->subMonth()->locale('id')->translatedFormat('F Y') }}</td>
        <td>{{ $previousBalance }}</td>
    </tr>

    {{-- Pemasukan Section --}}
    <tr>
        <td colspan="6">Pemasukan</td>
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
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
            <td>{{ $item->kodeakun->nama_akun ?? '-' }}</td>
            <td>{{ $item->keterangan }}</td>
            <td>{{ $item->nominal }}</td>
            <td></td>
        </tr>
    @endforeach
    <tr>
        <td colspan="5">Total Pemasukan</td>
        <td>{{ $totalPemasukan }}</td>
    </tr>

    {{-- Pengeluaran Section --}}
    <tr>
        <td colspan="6">Pengeluaran</td>
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
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
            <td>{{ $item->kodeakun->nama_akun ?? '-' }}</td>
            <td>{{ $item->keterangan }}</td>
            <td>{{ $item->nominal }}</td>
            <td></td>
        </tr>
    @endforeach
    <tr>
        <td colspan="5">Total Pengeluaran</td>
        <td>{{ $totalPengeluaran }}</td>
    </tr>

    {{-- Saldo Akhir --}}
    @php
        $saldoAkhir = $previousBalance + $totalPemasukan - $totalPengeluaran;
    @endphp
    <tr>
        <td colspan="5">Saldo Akhir</td>
        <td>{{ $saldoAkhir }}</td>
    </tr>
</table>
