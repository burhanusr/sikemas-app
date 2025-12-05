@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="mb-2 text-2xl font-bold text-white">Selamat datang, {{ Auth::user()->name }}</h2>
                    <p class="text-white/90">Berikut ini adalah ringkasan keuangan masjid hari ini.</p>
                </div>

            </div>
        </div>

        <!-- Financial Stats Grid -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Saldo Masjid -->
            {{-- <div class="rounded-xl border border-green-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Saldo Masjid</p>
                        <h3 class="mt-2 text-3xl font-bold text-gray-800">
                            Rp {{ number_format($saldoMasjid ?? 0, 0, ',', '.') }}
                        </h3>
                        <p class="mt-2 text-sm font-medium text-green-600">
                            <span class="inline-flex items-center">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                                Saldo Aktual
                            </span>
                        </p>
                    </div>
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-green-50 to-emerald-50">
                        <svg class="h-7 w-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div> --}}

            <!-- Pemasukan Masjid -->
            <div class="rounded-xl border border-green-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pemasukan Bulan Ini</p>
                        <h3 class="mt-2 text-3xl font-bold text-gray-800">
                            Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}
                        </h3>
                        <p class="mt-2 text-sm font-medium text-blue-600">
                            <span class="inline-flex items-center">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                </svg>
                                {{ $jumlahTransaksiPemasukan ?? 0 }} transaksi
                            </span>
                        </p>
                    </div>
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-blue-50 to-blue-100">
                        <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pengeluaran Masjid -->
            <div class="rounded-xl border border-green-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pengeluaran Bulan Ini</p>
                        <h3 class="mt-2 text-3xl font-bold text-gray-800">
                            Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}
                        </h3>
                        <p class="mt-2 text-sm font-medium text-orange-600">
                            <span class="inline-flex items-center">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                </svg>
                                {{ $jumlahTransaksiPengeluaran ?? 0 }} transaksi
                            </span>
                        </p>
                    </div>
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-orange-50 to-orange-100">
                        <svg class="h-7 w-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="rounded-xl border border-green-100 bg-white p-6 shadow-sm">
            <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <h3 class="text-lg font-semibold text-gray-800">Grafik Pemasukan & Pengeluaran</h3>
                <div class="flex flex-wrap gap-3">
                    <div class="flex items-center gap-2">
                        <label for="startDate" class="text-sm font-medium text-gray-600">Dari:</label>
                        <input type="date" id="startDate"
                            value="{{ $startDate ?? now()->startOfMonth()->format('Y-m-d') }}"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20">
                    </div>
                    <div class="flex items-center gap-2">
                        <label for="endDate" class="text-sm font-medium text-gray-600">Sampai:</label>
                        <input type="date" id="endDate" value="{{ $endDate ?? now()->format('Y-m-d') }}"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20">
                    </div>
                    <button onclick="updateChart()"
                        class="rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:from-green-600 hover:to-emerald-700">
                        <svg class="mr-1 inline-block h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Update
                    </button>
                </div>
            </div>
            <div class="relative" style="height: 400px; position: relative;">
                <canvas id="financialChart"
                    style="display: block; box-sizing: border-box; height: 400px; width: 100%;"></canvas>
            </div>
        </div>

        <!-- Recent Transactions & Quick Actions -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Recent Transactions -->
            <div class="rounded-xl border border-green-100 bg-white p-6 shadow-sm lg:col-span-2">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Transaksi Terbaru</h3>
                <div class="space-y-4">
                    @forelse($recentTransactions ?? [] as $transaction)
                        <div
                            class="{{ $transaction->jenis == 'pemasukan' ? 'bg-gradient-to-r from-blue-50 to-blue-100' : 'bg-gradient-to-r from-orange-50 to-orange-100' }} flex items-start space-x-4 rounded-lg p-4">
                            <div
                                class="{{ $transaction->jenis == 'pemasukan' ? 'bg-gradient-to-br from-blue-500 to-blue-600' : 'bg-gradient-to-br from-orange-500 to-orange-600' }} flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full shadow-sm">
                                @if ($transaction->jenis == 'pemasukan')
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ ucfirst($transaction->jenis) }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $transaction->keterangan ?? 'Tidak ada keterangan' }}</p>
                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($transaction->tanggal)->format('d F Y') }}</p>
                                    </div>
                                    <p
                                        class="{{ $transaction->jenis == 'pemasukan' ? 'text-blue-600' : 'text-orange-600' }} text-lg font-bold">
                                        {{ $transaction->jenis == 'pemasukan' ? '+' : '-' }} Rp
                                        {{ number_format($transaction->nominal, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg bg-gray-50 p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Belum ada transaksi</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="rounded-xl border border-green-100 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ url('/admin/kas/pemasukan') }}"
                        class="flex w-full transform items-center justify-center rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 px-4 py-3 font-semibold text-white shadow-sm transition-all hover:scale-[1.02] hover:from-blue-600 hover:to-blue-700 hover:shadow-md active:scale-[0.98]">
                        <svg class="mr-2 size-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pemasukan Masjid
                    </a>
                    <a href="{{ url('/admin/kas/pengeluaran') }}"
                        class="flex w-full transform items-center justify-center rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 px-4 py-3 font-semibold text-white shadow-sm transition-all hover:scale-[1.02] hover:from-orange-600 hover:to-orange-700 hover:shadow-md active:scale-[0.98]">
                        <svg class="mr-2 size-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Pengeluaran Masjid
                    </a>
                    <a href="{{ route('jurnal-umum.index') }}"
                        class="flex w-full items-center justify-center rounded-lg border-2 border-green-200 bg-white px-4 py-3 font-semibold text-gray-700 transition-all hover:border-green-300 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Lihat Jurnal Umum
                    </a>
                    <a href="{{ route('kode-akun.index') }}"
                        class="flex w-full items-center justify-center rounded-lg border-2 border-green-200 bg-white px-4 py-3 font-semibold text-gray-700 transition-all hover:border-green-300 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Kelola Kode Akun
                    </a>

                </div>
            </div>
        </div>
    </div>

    <!-- Load Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <!-- Chart Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart data from controller
            const chartData = @json($chartData ?? ['labels' => [], 'pemasukan' => [], 'pengeluaran' => []]);

            // Get canvas element
            const canvasElement = document.getElementById('financialChart');

            if (canvasElement && typeof Chart !== 'undefined') {
                const ctx = canvasElement.getContext('2d');
                const financialChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels || [],
                        datasets: [{
                                label: 'Pemasukan',
                                data: chartData.pemasukan || [],
                                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 1,
                                borderRadius: 6
                            },
                            {
                                label: 'Pengeluaran',
                                data: chartData.pengeluaran || [],
                                backgroundColor: 'rgba(249, 115, 22, 0.8)',
                                borderColor: 'rgb(249, 115, 22)',
                                borderWidth: 1,
                                borderRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    font: {
                                        size: 12,
                                        weight: '600'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });

        function updateChart() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!startDate || !endDate) {
                alert('Silakan pilih tanggal mulai dan tanggal akhir');
                return;
            }

            window.location.href = `{{ route('admin.dashboard') }}?start_date=${startDate}&end_date=${endDate}`;
        }
    </script>
@endsection
