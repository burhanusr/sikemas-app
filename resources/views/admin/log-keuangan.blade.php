@extends('layouts.app')

@section('title', 'Log Aktivitas Keuangan')

@section('content')
    <div class="rounded-xl border border-blue-100 bg-white shadow-lg">
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
                    <h2 class="text-2xl font-bold text-gray-800">Log Aktivitas Keuangan</h2>
                    <p class="mt-1 text-gray-600">Riwayat semua transaksi keuangan yang telah dilakukan</p>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Export Excel Button -->
                    <button onclick="openExportModal('excel')" id="excelButton"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white shadow transition hover:bg-green-600 disabled:cursor-not-allowed disabled:opacity-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span id="excelButtonText">Export Excel</span>
                    </button>

                    <!-- Export PDF Button -->
                    <button onclick="openExportModal('pdf')" id="pdfButton"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white shadow transition hover:bg-red-600 disabled:cursor-not-allowed disabled:opacity-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span id="pdfButtonText">Export PDF</span>
                    </button>

                    <span
                        class="inline-flex items-center rounded-lg bg-green-50 px-3 py-2 text-sm font-medium text-green-700">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Total: {{ $kas->total() }} Transaksi
                    </span>
                </div>
            </div>

            <!-- Balance Information Card -->
            <div class="rounded-xl border border-green-200 bg-gradient-to-br from-green-50 to-indigo-50 p-6 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-green-500 shadow-lg">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Saldo Saat Ini</p>
                            <p class="text-3xl font-bold text-green-600">
                                Rp {{ number_format($saldo ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Summary Statistics -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Total Pemasukan -->
                        <div class="rounded-lg bg-white p-4 shadow-sm">
                            <div class="flex items-center gap-2">
                                <div class="rounded-full bg-green-100 p-2">
                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Total Pemasukan</p>
                                    <p class="text-sm font-bold text-green-600">
                                        Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Total Pengeluaran -->
                        <div class="rounded-lg bg-white p-4 shadow-sm">
                            <div class="flex items-center gap-2">
                                <div class="rounded-full bg-orange-100 p-2">
                                    <svg class="h-4 w-4 text-orange-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Total Pengeluaran</p>
                                    <p class="text-sm font-bold text-orange-600">
                                        Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="rounded-xl border border-blue-100 bg-white shadow-sm">
                <!-- Filter Section -->
                <div class="border-b border-blue-100 p-4">
                    <form method="GET" action="{{ route('kas.index') }}" id="filterForm">
                        @if (isset($user) && $user->id !== Auth::id())
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                        @endif

                        <div class="flex flex-col gap-4">
                            <!-- First Row: Search and Type -->
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                <!-- Search Input -->
                                <div class="flex-1">
                                    <div class="relative">
                                        <input type="text" name="search" id="searchInput"
                                            value="{{ request('search') }}"
                                            placeholder="Cari nama akun atau keterangan..."
                                            class="w-full rounded-lg border border-blue-200 py-2 pl-10 pr-4 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Filter Jenis -->
                                <div class="w-full sm:w-48">
                                    <select name="jenis" id="filterJenis"
                                        class="w-full rounded-lg border border-blue-200 px-4 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Semua Jenis</option>
                                        <option value="pemasukan" {{ request('jenis') == 'pemasukan' ? 'selected' : '' }}>
                                            Pemasukan</option>
                                        <option value="pengeluaran"
                                            {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Second Row: Date Range and Buttons -->
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                <!-- Filter Tanggal Dari -->
                                <div class="flex-1">
                                    <input type="date" name="tanggal_dari" id="filterTanggalDari"
                                        value="{{ request('tanggal_dari') }}" placeholder="Tanggal dari"
                                        class="w-full rounded-lg border border-blue-200 px-4 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <!-- Filter Tanggal Sampai -->
                                <div class="flex-1">
                                    <input type="date" name="tanggal_sampai" id="filterTanggalSampai"
                                        value="{{ request('tanggal_sampai') }}" placeholder="Tanggal sampai"
                                        class="w-full rounded-lg border border-blue-200 px-4 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <!-- Buttons -->
                                <div class="flex gap-2">
                                    @if (request()->hasAny(['search', 'jenis', 'tanggal_dari', 'tanggal_sampai']))
                                        <a href="{{ route('kas.index') }}{{ isset($user) && $user->id !== Auth::id() ? '?user_id=' . $user->id : '' }}"
                                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Reset
                                        </a>
                                    @endif

                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Cari
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Active Filters Badge -->
                        @if (request()->hasAny(['search', 'jenis', 'tanggal_dari', 'tanggal_sampai']))
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <span class="text-sm font-medium text-gray-600">Filter aktif:</span>

                                @if (request('search'))
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Pencarian: "{{ request('search') }}"
                                    </span>
                                @endif

                                @if (request('jenis'))
                                    <span
                                        class="{{ request('jenis') == 'pemasukan' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }} inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-medium">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ request('jenis') == 'pemasukan' ? 'M7 11l5-5m0 0l5 5m-5-5v12' : 'M17 13l-5 5m0 0l-5-5m5 5V6' }}" />
                                        </svg>
                                        Jenis: {{ ucfirst(request('jenis')) }}
                                    </span>
                                @endif

                                @if (request('tanggal_dari'))
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800">
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
                                        class="inline-flex items-center gap-1 rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800">
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

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-blue-100">
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    No</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Jenis</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Nama Akun</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Kode Akun</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Keterangan</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Nominal</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-100">
                            @forelse($kas as $index => $item)
                                <tr class="transition-colors hover:bg-blue-50/50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $kas->firstItem() + $index }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @if ($item->jenis == 'pemasukan')
                                            <span
                                                class="inline-flex items-center rounded-full bg-gradient-to-r from-green-100 to-emerald-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                                </svg>
                                                Pemasukan
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full bg-gradient-to-r from-orange-100 to-orange-100 px-3 py-1 text-xs font-semibold text-orange-700">
                                                <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                                </svg>
                                                Pengeluaran
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                        {{ $item->kodeAkun->nama_akun }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-full bg-gradient-to-r from-blue-100 to-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                            {{ $item->kodeAkun->kode_akun ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ Str::limit($item->keterangan ?: '-', 50) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold">
                                        <span
                                            class="{{ $item->jenis == 'pemasukan' ? 'text-green-600' : 'text-orange-600' }}">
                                            {{ $item->jenis == 'pemasukan' ? '+' : '-' }} Rp
                                            {{ number_format($item->nominal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <button onclick='openDetailModal(@json($item))'
                                            class="inline-flex items-center rounded-lg bg-purple-500 px-3 py-1.5 text-sm font-medium text-white transition-all hover:bg-purple-600 hover:shadow-md">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500">
                                            @if (request()->hasAny(['search', 'jenis', 'tanggal_dari', 'tanggal_sampai']))
                                                Tidak ada data yang sesuai dengan filter
                                            @else
                                                Belum ada aktivitas keuangan
                                            @endif
                                        </p>
                                        <p class="mt-1 text-gray-400">
                                            @if (request()->hasAny(['search', 'jenis', 'tanggal_dari', 'tanggal_sampai']))
                                                Coba ubah atau reset filter pencarian
                                            @else
                                                Transaksi yang Anda lakukan akan muncul di sini
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($kas->hasPages())
                    <div class="mt-6 rounded-xl border border-gray-100 bg-white p-4 shadow-sm">
                        {{ $kas->links('components.pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div id="exportModal"
        class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center bg-black/30 p-4 backdrop-blur-sm">
        <div class="modal-content w-full max-w-md transform rounded-xl bg-white shadow-2xl"
            onclick="event.stopPropagation()">
            <div id="modalHeader" class="rounded-t-xl px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 id="modalTitle" class="text-xl font-bold text-white"></h3>
                        <p id="modalSubtitle" class="mt-1"></p>
                    </div>
                    <button onclick="closeExportModal()" class="text-white transition-colors hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <form id="exportForm" method="GET" class="p-6">
                @if (isset($user) && $user->id !== Auth::id())
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                @endif

                <div class="space-y-4">
                    <!-- Month Selection -->
                    <div>
                        <label for="export_month" class="mb-2 block text-sm font-medium text-gray-700">Bulan</label>
                        <select name="month" id="export_month"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year Selection -->
                    <div>
                        <label for="export_year" class="mb-2 block text-sm font-medium text-gray-700">Tahun</label>
                        <select name="year" id="export_year"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @foreach (range(now()->year - 5, now()->year + 1) as $y)
                                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeExportModal()"
                        class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" id="exportButton"
                        class="flex-1 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition-all">
                        <svg class="mr-2 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span id="exportButtonText">Export</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal"
        class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center bg-black/30 p-4 backdrop-blur-sm">
        <div class="modal-content w-full max-w-2xl transform rounded-xl bg-white shadow-2xl"
            onclick="event.stopPropagation()">
            <div class="rounded-t-xl bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">Detail Transaksi</h3>
                        <p class="mt-1 text-purple-50">Informasi lengkap aktivitas keuangan</p>
                    </div>
                    <button onclick="closeDetailModal()" class="text-white transition-colors hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="max-h-[calc(100vh-250px)] overflow-y-auto p-6">
                <div class="space-y-4">
                    <!-- Jenis Badge -->
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <label class="mb-1 flex items-center gap-1.5 text-xs font-medium text-gray-500">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Jenis Transaksi
                        </label>
                        <p id="detail_jenis" class="font-semibold text-gray-900"></p>
                    </div>

                    <!-- Nominal -->
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <label class="mb-1 flex items-center gap-1.5 text-xs font-medium text-gray-500">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Nominal
                        </label>
                        <p id="detail_nominal" class="text-lg font-bold"></p>
                    </div>

                    <!-- Description -->
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <label class="mb-1 flex items-center gap-1.5 text-xs font-medium text-gray-500">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Keterangan
                        </label>
                        <p id="detail_keterangan"
                            class="whitespace-pre-wrap break-words text-sm leading-relaxed text-gray-700"></p>
                    </div>

                    <!-- Timestamps -->
                    <div class="grid gap-4 rounded-lg border border-gray-200 bg-gray-50 p-3 md:grid-cols-2">
                        <div>
                            <label class="mb-1 flex items-center gap-1.5 text-xs font-medium text-gray-400">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Dibuat
                            </label>
                            <p id="detail_created" class="text-sm text-gray-600"></p>
                        </div>
                        <div>
                            <label class="mb-1 flex items-center gap-1.5 text-xs font-medium text-gray-400">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Diperbarui
                            </label>
                            <p id="detail_updated" class="text-sm text-gray-600"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-6 pt-0">
                <button type="button" onclick="closeDetailModal()"
                    class="w-full rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-3 font-semibold text-white transition-all hover:from-purple-600 hover:to-purple-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentExportType = 'excel';
        let isExporting = false;

        function openExportModal(type) {
            if (isExporting) return; // Prevent opening modal while exporting

            currentExportType = type;
            const modal = document.getElementById('exportModal');
            const form = document.getElementById('exportForm');
            const header = document.getElementById('modalHeader');
            const title = document.getElementById('modalTitle');
            const subtitle = document.getElementById('modalSubtitle');
            const button = document.getElementById('exportButton');
            const buttonText = document.getElementById('exportButtonText');

            if (type === 'excel') {
                form.action = '{{ route('kas.export') }}';
                header.className = 'rounded-t-xl bg-gradient-to-r from-green-500 to-green-600 px-6 py-5';
                title.textContent = 'Export Laporan Excel';
                subtitle.textContent = 'Pilih bulan dan tahun untuk export';
                subtitle.className = 'mt-1 text-green-50';
                button.className =
                    'flex-1 rounded-lg bg-gradient-to-r from-green-500 to-green-600 px-4 py-2.5 text-sm font-medium text-white transition-all hover:from-green-600 hover:to-green-700 disabled:opacity-50 disabled:cursor-not-allowed';
                buttonText.textContent = 'Export Excel';
            } else {
                form.action = '{{ route('kas.export.pdf') }}';
                header.className = 'rounded-t-xl bg-gradient-to-r from-red-500 to-red-600 px-6 py-5';
                title.textContent = 'Export Laporan PDF';
                subtitle.textContent = 'Pilih bulan dan tahun untuk export';
                subtitle.className = 'mt-1 text-red-50';
                button.className =
                    'flex-1 rounded-lg bg-gradient-to-r from-red-500 to-red-600 px-4 py-2.5 text-sm font-medium text-white transition-all hover:from-red-600 hover:to-red-700 disabled:opacity-50 disabled:cursor-not-allowed';
                buttonText.textContent = 'Export PDF';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeExportModal() {
            if (isExporting) return; // Prevent closing modal while exporting

            const modal = document.getElementById('exportModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Handle form submission with loading state
        document.addEventListener('DOMContentLoaded', function() {
            const exportForm = document.getElementById('exportForm');

            if (exportForm) {
                exportForm.addEventListener('submit', function(e) {
                    if (isExporting) {
                        e.preventDefault();
                        return;
                    }

                    // Set exporting state
                    isExporting = true;

                    // Disable buttons
                    const excelButton = document.getElementById('excelButton');
                    const pdfButton = document.getElementById('pdfButton');
                    const exportButton = document.getElementById('exportButton');
                    const exportButtonText = document.getElementById('exportButtonText');

                    if (excelButton) excelButton.disabled = true;
                    if (pdfButton) pdfButton.disabled = true;
                    if (exportButton) exportButton.disabled = true;

                    // Update button text with loading indicator
                    const originalText = exportButtonText.textContent;
                    exportButtonText.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `;

                    // Close modal after a short delay
                    setTimeout(() => {
                        closeExportModal();

                        // Reset button states after download completes (estimated 3 seconds)
                        setTimeout(() => {
                            isExporting = false;
                            if (excelButton) excelButton.disabled = false;
                            if (pdfButton) pdfButton.disabled = false;
                            if (exportButton) exportButton.disabled = false;
                            exportButtonText.textContent = originalText;
                        }, 3000);
                    }, 500);
                });
            }

            // Other existing code...
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('filterForm').submit();
                    }
                });
            }

            const detailModal = document.getElementById('detailModal');
            if (detailModal) {
                detailModal.addEventListener('click', function(e) {
                    if (e.target === this) closeDetailModal();
                });
            }

            const exportModal = document.getElementById('exportModal');
            if (exportModal) {
                exportModal.addEventListener('click', function(e) {
                    if (e.target === this && !isExporting) closeExportModal();
                });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !isExporting) {
                    closeDetailModal();
                    closeExportModal();
                }
            });
        });

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        function openDetailModal(item) {
            const jenisContainer = document.getElementById('detail_jenis');
            if (item.jenis == 'pemasukan') {
                jenisContainer.innerHTML =
                    '<span class="inline-flex items-center rounded-full bg-gradient-to-r from-green-100 to-emerald-100 px-3 py-1.5 text-sm font-semibold text-green-700"><svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" /></svg>Pemasukan</span>';
            } else {
                jenisContainer.innerHTML =
                    '<span class="inline-flex items-center rounded-full bg-gradient-to-r from-orange-100 to-orange-100 px-3 py-1.5 text-sm font-semibold text-orange-700"><svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" /></svg>Pengeluaran</span>';
            }

            const nominalElement = document.getElementById('detail_nominal');
            if (item.jenis == 'pemasukan') {
                nominalElement.className = 'text-lg font-bold text-green-600';
                nominalElement.textContent = '+ ' + formatRupiah(item.nominal);
            } else {
                nominalElement.className = 'text-lg font-bold text-orange-600';
                nominalElement.textContent = '- ' + formatRupiah(item.nominal);
            }

            document.getElementById('detail_keterangan').textContent = item.keterangan || '-';
            document.getElementById('detail_created').textContent = new Date(item.created_at).toLocaleString('id-ID');
            document.getElementById('detail_updated').textContent = new Date(item.updated_at).toLocaleString('id-ID');

            const modal = document.getElementById('detailModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
