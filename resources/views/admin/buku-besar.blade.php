@extends('layouts.app')

@section('title', 'Buku Besar')

@section('content')
    <div class="rounded-xl border border-purple-100 bg-white shadow-lg">
        <div class="space-y-6 p-6">
            <!-- Viewing Context Banner (for Superadmin) -->
            @if (isset($user) && $user->id !== Auth::id())
                <div
                    class="animate-fade-in rounded-lg border border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 p-4 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-blue-900">Mode Tampilan Data</h4>
                            <p class="mt-1 text-sm text-blue-700">
                                Anda sedang melihat data untuk: <strong>{{ $user->organization }}</strong>
                                ({{ $user->name }} - {{ $user->email }})
                            </p>
                            <p class="mt-1 text-xs text-blue-600">
                                <svg class="mr-1 inline h-3.5 w-3.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Mode Read-Only: Anda tidak dapat mengubah data ini
                            </p>
                        </div>
                        <a href="{{ route('superadmin.laporan-keuangan') }}"
                            class="flex-shrink-0 rounded-lg bg-blue-100 px-3 py-2 text-sm font-medium text-blue-700 transition-colors hover:bg-blue-200">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            @endif

            <!-- Header -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Buku Besar</h2>
                    <p class="mt-1 text-gray-600">Rincian transaksi per akun dengan saldo berjalan</p>
                </div>
                @if ($selectedAkun)
                    <div class="rounded-lg border border-purple-200 bg-purple-50 px-4 py-2">
                        <p class="text-sm font-semibold text-purple-800">
                            Akun: <span class="text-base">{{ $selectedAkun->kode_akun }} -
                                {{ $selectedAkun->nama_akun }}</span>
                        </p>
                    </div>
                @endif
            </div>

            @if (session('success'))
                <div
                    class="animate-fade-in flex items-center rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Table Card -->
            <div class="rounded-xl border border-purple-100 bg-white shadow-sm">
                <!-- Filter Section -->
                <div class="border-b border-purple-100 bg-purple-50/30 p-4">
                    <form method="GET" action="{{ route('buku-besar.index') }}" id="filterForm">
                        @if (isset($user) && $user->id !== Auth::id())
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                        @endif

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
                            <!-- Filter Kode Akun (Required) -->
                            <div class="flex-1">
                                <x-autocomplete id="filterKodeAkun" name="kode_akun" label="Kode Akun" color="purple"
                                    size="small" placeholder="Pilih kode akun..." required="true">
                                    <option value="">-- Pilih Kode Akun --</option>
                                    @foreach ($kodeAkun as $kode)
                                        <option value="{{ $kode->id }}"
                                            {{ request('kode_akun') == $kode->id ? 'selected' : '' }}>
                                            {{ $kode->kode_akun }} - {{ $kode->nama_akun }}
                                        </option>
                                    @endforeach
                                </x-autocomplete>
                            </div>

                            <!-- Filter Tanggal Dari -->
                            <div class="w-full sm:w-48">
                                <x-input-date id="filterTanggalDari" name="tanggal_dari" label="Tanggal Dari" color="purple"
                                    value="{{ request('tanggal_dari') }}" size="small" />
                            </div>

                            <!-- Filter Tanggal Sampai -->
                            <div class="w-full sm:w-48">
                                <x-input-date id="filterTanggalSampai" name="tanggal_sampai" label="Tanggal Sampai"
                                    color="purple" value="{{ request('tanggal_sampai') }}" size="small" />
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-2">
                                @if (request()->hasAny(['kode_akun', 'tanggal_dari', 'tanggal_sampai']))
                                    <a href="{{ route('buku-besar.index') }}{{ isset($user) && $user->id !== Auth::id() ? '?user_id=' . $user->id : '' }}"
                                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                        <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Reset
                                    </a>
                                @endif

                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-lg bg-purple-500 px-4 py-2 text-sm font-medium text-white hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                    <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Tampilkan
                                </button>
                            </div>
                        </div>

                        <!-- Active Filters Badge -->
                        @if (request()->hasAny(['kode_akun', 'tanggal_dari', 'tanggal_sampai']))
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <span class="text-sm font-medium text-gray-600">Filter aktif:</span>

                                @if (request('kode_akun'))
                                    @php
                                        $selectedKode = $kodeAkun->find(request('kode_akun'));
                                    @endphp
                                    @if ($selectedKode)
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                            </svg>
                                            Kode: {{ $selectedKode->kode_akun }}
                                        </span>
                                    @endif
                                @endif

                                @if (request('tanggal_dari'))
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Dari: {{ \Carbon\Carbon::parse(request('tanggal_dari'))->format('d M Y') }}
                                    </span>
                                @endif

                                @if (request('tanggal_sampai'))
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-orange-800">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Sampai: {{ \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </form>
                </div>

                @if ($selectedAkun)
                    <!-- Saldo Awal Card -->
                    @if ($saldoAwal != 0 || request('tanggal_dari'))
                        <div class="border-b border-purple-100 bg-gradient-to-r from-purple-50 to-indigo-50 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-semibold text-purple-800">Saldo Awal</span>
                                    @if (request('tanggal_dari'))
                                        <span class="text-sm text-purple-600">
                                            (sebelum {{ \Carbon\Carbon::parse(request('tanggal_dari'))->format('d M Y') }})
                                        </span>
                                    @endif
                                </div>
                                <span class="{{ $saldoAwal >= 0 ? 'text-green-600' : 'text-red-600' }} text-lg font-bold">
                                    Rp {{ number_format(abs($saldoAwal), 0, ',', '.') }}
                                    {{ $saldoAwal < 0 ? '(Kredit)' : '(Debit)' }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-purple-100 bg-purple-50">
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                        No</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                        Keterangan</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-700">
                                        Debit</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-700">
                                        Kredit</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-700">
                                        Saldo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-purple-100">
                                @forelse($bukuBesar as $index => $item)
                                    <tr class="transition-colors hover:bg-purple-50/50">
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-800">
                                            {{ $item->keterangan ?: '-' }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold text-green-600">
                                            @if ($item->debit > 0)
                                                Rp {{ number_format($item->debit, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold text-red-600">
                                            @if ($item->kredit > 0)
                                                Rp {{ number_format($item->kredit, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td
                                            class="{{ $item->saldo >= 0 ? 'text-blue-600' : 'text-orange-600' }} whitespace-nowrap px-6 py-4 text-right text-sm font-bold">
                                            Rp {{ number_format(abs($item->saldo), 0, ',', '.') }}
                                            <span class="text-xs">{{ $item->saldo < 0 ? '(K)' : '(D)' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-lg font-medium text-gray-500">
                                                Tidak ada transaksi untuk akun ini
                                            </p>
                                            <p class="mt-1 text-gray-400">
                                                @if (request('tanggal_dari') || request('tanggal_sampai'))
                                                    Pada periode yang dipilih
                                                @else
                                                    Belum ada transaksi yang tercatat
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse

                                @if ($bukuBesar->count() > 0)
                                    <tr class="border-t-2 border-purple-300 bg-purple-50 font-bold">
                                        <td colspan="3" class="px-6 py-4 text-right text-sm text-gray-800">
                                            TOTAL:
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-green-600">
                                            Rp {{ number_format($totalDebit, 0, ',', '.') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-red-600">
                                            Rp {{ number_format($totalKredit, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4"></td>
                                    </tr>
                                    <tr class="border-t border-purple-200 bg-purple-100 font-bold">
                                        <td colspan="3" class="px-6 py-4 text-right text-sm text-gray-800">
                                            SALDO AKHIR:
                                        </td>
                                        <td colspan="2" class="px-6 py-4"></td>
                                        <td
                                            class="{{ $saldoAkhir >= 0 ? 'text-blue-600' : 'text-orange-600' }} whitespace-nowrap px-6 py-4 text-right text-base">
                                            Rp {{ number_format(abs($saldoAkhir), 0, ',', '.') }}
                                            <span class="text-sm">{{ $saldoAkhir < 0 ? '(Kredit)' : '(Debit)' }}</span>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="px-6 py-16 text-center">
                        <svg class="mx-auto mb-4 h-20 w-20 text-purple-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="mb-2 text-xl font-semibold text-gray-700">Pilih Kode Akun</h3>
                        <p class="text-gray-500">Silakan pilih kode akun untuk melihat buku besar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Restore autocomplete value on page load
            const filterKodeAkunInput = document.getElementById('filterKodeAkun');
            const filterKodeAkunHidden = document.getElementById('filterKodeAkun-value');

            @if (request('kode_akun'))
                if (filterKodeAkunHidden) {
                    filterKodeAkunHidden.value = "{{ request('kode_akun') }}";

                    @php
                        $selectedKode = $kodeAkun->find(request('kode_akun'));
                    @endphp

                    @if ($selectedKode)
                        if (filterKodeAkunInput) {
                            filterKodeAkunInput.value =
                                "{{ $selectedKode->kode_akun }} - {{ $selectedKode->nama_akun }}";
                        }
                    @endif
                }
            @endif
        });
    </script>
@endsection
