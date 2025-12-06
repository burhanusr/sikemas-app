@extends('layouts.app')

@section('title', 'Dashboard Super Admin')
@section('page-title', 'Dashboard Super Admin')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="mb-2 text-2xl font-bold text-white">Selamat datang, {{ Auth::user()->name }}!</h2>
                    <p class="text-white/90">Anda memiliki akses penuh ke seluruh fitur sistem.</p>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Total Users -->
            <div class="rounded-xl border border-purple-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                        <h3 class="mt-2 text-3xl font-bold text-gray-800">{{ $totalUsers ?? 0 }}</h3>
                        <p class="mt-2 text-sm font-medium text-purple-600">
                            <span class="inline-flex items-center">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Pengguna
                            </span>
                        </p>
                    </div>
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-purple-50 to-purple-100">
                        <svg class="h-7 w-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Superadmin -->
            <div class="rounded-xl border border-indigo-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Jumlah Admin PRM</p>
                        <h3 class="mt-2 text-3xl font-bold text-gray-800">{{ $totalSuperadmin ?? 0 }}</h3>
                        <p class="mt-2 text-sm font-medium text-indigo-600">
                            <span class="inline-flex items-center">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Super Admin
                            </span>
                        </p>
                    </div>
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-indigo-50 to-indigo-100">
                        <svg class="h-7 w-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Admin Masjid -->
            <div class="rounded-xl border border-green-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Jumlah Admin Masjid</p>
                        <h3 class="mt-2 text-3xl font-bold text-gray-800">{{ $totalAdmin ?? 0 }}</h3>
                        <p class="mt-2 text-sm font-medium text-green-600">
                            <span class="inline-flex items-center">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Admin Masjid
                            </span>
                        </p>
                    </div>
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-green-50 to-emerald-50">
                        <svg class="h-7 w-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Comparison Chart -->
        <div class="rounded-xl border border-purple-100 bg-white p-6 shadow-sm">
            <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <h3 class="text-lg font-semibold text-gray-800">Grafik {{ ucfirst($transactionType ?? 'Pemasukan') }} Per
                    Masjid (Januari - Desember {{ $year ?? now()->year }})</h3>
                <div class="flex flex-wrap gap-3">
                    <div class="flex items-center gap-2">
                        <label for="transactionType" class="text-sm font-medium text-gray-600">Jenis:</label>
                        <select id="transactionType"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                            <option value="pemasukan"
                                {{ ($transactionType ?? 'pemasukan') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran"
                                {{ ($transactionType ?? 'pemasukan') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran
                            </option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label for="year" class="text-sm font-medium text-gray-600">Tahun:</label>
                        <select id="year"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                            @foreach ($availableYears as $availableYear)
                                <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                    {{ $availableYear }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button onclick="updateChart()"
                        class="rounded-lg bg-gradient-to-r from-purple-600 to-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:from-purple-700 hover:to-indigo-700">
                        <svg class="mr-1 inline-block h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Update
                    </button>
                </div>
            </div>
            <div id="chartWrapper" style="width: 100%; height: 400px; position: relative; overflow: hidden;">
                <canvas id="adminChart"></canvas>
            </div>
        </div>

        <!-- System Stats & Recent Activity -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Financial Summary -->
            <div class="rounded-xl border border-purple-100 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Ringkasan Keuangan Semua Masjid</h3>
                <div class="space-y-4">
                    <div class="rounded-lg border border-blue-200 bg-gradient-to-r from-blue-50 to-blue-100 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Pemasukan</p>
                                    <p class="text-xs text-gray-500">Bulan ini</p>
                                </div>
                            </div>
                            <p class="text-xl font-bold text-blue-600">
                                Rp {{ number_format($totalPemasukanAll ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-orange-200 bg-gradient-to-r from-orange-50 to-orange-100 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-500">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                                    <p class="text-xs text-gray-500">Bulan ini</p>
                                </div>
                            </div>
                            <p class="text-xl font-bold text-orange-600">
                                Rp {{ number_format($totalPengeluaranAll ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-green-200 bg-gradient-to-r from-green-50 to-emerald-50 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-500">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Saldo Semua Masjid</p>
                                    <p class="text-xs text-gray-500">Saldo aktual</p>
                                </div>
                            </div>
                            <p class="text-xl font-bold text-green-600">
                                Rp {{ number_format($totalSaldoAll ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Admin Activity -->
            <div class="rounded-xl border border-purple-100 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Aktivitas Admin Terbaru</h3>
                <div class="space-y-4">
                    @forelse($recentAdminActivities ?? [] as $activity)
                        <div
                            class="flex items-start space-x-4 rounded-lg border border-purple-200 bg-gradient-to-r from-purple-50 to-purple-100 p-4">
                            <div
                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-purple-500 to-purple-600 shadow-sm">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $activity->name }}</p>
                                <p class="text-sm text-gray-600">{{ $activity->organization ?? 'Tidak ada organisasi' }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">Bergabung
                                    {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</p>
                            </div>
                            <span class="rounded-full bg-purple-200 px-3 py-1 text-xs font-semibold text-purple-700">
                                {{ ucfirst($activity->role) }}
                            </span>
                        </div>
                    @empty
                        <div class="rounded-lg bg-gray-50 p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Belum ada aktivitas admin</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Admin Data Table -->
        <div class="rounded-xl border border-purple-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Detail {{ ucfirst($transactionType ?? 'Pemasukan') }}
                Admin Tahun {{ $year ?? now()->year }}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama
                                Admin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Organisasi</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                                Total {{ ucfirst($transactionType ?? 'Pemasukan') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($adminData ?? [] as $index => $admin)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $admin['name'] }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $admin['organization'] }}
                                </td>
                                <td
                                    class="{{ ($transactionType ?? 'pemasukan') == 'pemasukan' ? 'text-blue-600' : 'text-orange-600' }} whitespace-nowrap px-6 py-4 text-right text-sm font-semibold">
                                    Rp {{ number_format($admin['total'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                    Tidak ada data tersedia
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Load Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <!-- Chart Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart data from controller
            const chartData = @json($chartData ?? ['labels' => [], 'datasets' => [], 'type' => 'pemasukan']);

            // Get canvas element
            const canvasElement = document.getElementById('adminChart');
            const wrapper = document.getElementById('chartWrapper');

            if (canvasElement && typeof Chart !== 'undefined') {
                // Set explicit canvas dimensions
                const wrapperWidth = wrapper.offsetWidth;
                const wrapperHeight = wrapper.offsetHeight;

                canvasElement.width = wrapperWidth;
                canvasElement.height = wrapperHeight;
                canvasElement.style.width = wrapperWidth + 'px';
                canvasElement.style.height = wrapperHeight + 'px';

                const ctx = canvasElement.getContext('2d');

                // Destroy existing chart if it exists
                if (window.adminChartInstance) {
                    window.adminChartInstance.destroy();
                }

                window.adminChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.labels || [],
                        datasets: chartData.datasets || []
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        animation: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    font: {
                                        size: 11,
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

                // Handle window resize manually
                let resizeTimeout;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(function() {
                        if (window.adminChartInstance) {
                            const newWidth = wrapper.offsetWidth;
                            const newHeight = wrapper.offsetHeight;

                            canvasElement.width = newWidth;
                            canvasElement.height = newHeight;
                            canvasElement.style.width = newWidth + 'px';
                            canvasElement.style.height = newHeight + 'px';

                            window.adminChartInstance.resize();
                        }
                    }, 250);
                });
            }
        });

        // Update chart function
        function updateChart() {
            const year = document.getElementById('year').value;
            const transactionType = document.getElementById('transactionType').value;

            if (!year) {
                alert('Silakan pilih tahun');
                return;
            }

            window.location.href = `{{ route('superadmin.dashboard') }}?year=${year}&transaction_type=${transactionType}`;
        }
    </script>
@endsection
