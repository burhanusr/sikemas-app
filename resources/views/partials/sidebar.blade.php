<aside id="sidebar"
    class="fixed z-30 h-full w-72 flex-shrink-0 -translate-x-full border-r border-green-100 bg-white shadow-lg transition-all duration-300 ease-in-out md:relative md:translate-x-0">
    <div class="flex h-full flex-col">
        <!-- Logo & Organization Section -->
        <div class="border-b border-green-100">
            <!-- Logo -->
            <div class="flex h-20 items-center justify-center px-4">
                <img id="logo" src="{{ asset('images/sikemas-logo.png') }}" alt="SIKEMAS" class="h-12 w-auto">
                <div id="logo-icon" class="hidden">
                    <img src="{{ asset('images/sikemas-logo-2.png') }}" alt="SIKEMAS" class="h-12 w-auto">
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-3 py-6">
            <ul class="space-y-2">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route(Auth::user()->isSuperAdmin() ? 'superadmin.dashboard' : 'admin.dashboard') }}"
                        class="{{ request()->routeIs('*.dashboard') ? 'bg-gradient-to-r from-green-50 to-emerald-50 text-green-600 font-medium' : '' }} flex items-center rounded-lg px-4 py-3 text-gray-700 transition-all hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="sidebar-text ml-3 font-medium">Dashboard</span>
                    </a>
                </li>

                @if (Auth::user()->isSuperAdmin())
                    <!-- Super Admin Menus -->
                    <li>
                        <a href="{{ route('users.index') }}"
                            class="flex items-center rounded-lg px-4 py-3 text-gray-700 transition-all hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600">
                            <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="sidebar-text ml-3 font-medium">Data User</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('superadmin.laporan-keuangan') }}"
                            class="flex items-center rounded-lg px-4 py-3 text-gray-700 transition-all hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600">
                            <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span class="sidebar-text ml-3 font-medium">Laporan Keuangan Masjid</span>
                        </a>
                    </li>
                @else
                    <!-- Admin Menus -->
                    <!-- Input Data Transaksi Dropdown -->
                    <li>
                        <button onclick="toggleDropdown('inputDataDropdown')"
                            class="flex w-full items-center justify-between rounded-lg px-4 py-3 text-gray-700 transition-all hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="sidebar-text ml-3 font-medium">Input Data Transaksi</span>
                            </div>
                            <svg id="inputDataDropdown-icon" class="sidebar-text h-4 w-4 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul id="inputDataDropdown" class="ml-4 mt-2 hidden space-y-1">
                            <li>
                                <a href={{ route('kode-akun.index') }}
                                    class="sidebar-text flex items-center rounded-lg px-4 py-2.5 text-sm text-gray-600 transition-all hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600">
                                    <svg class="mr-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span>Master Data / Kode Akun</span>
                                </a>
                            </li>
                            <li>
                                <a href={{ url('/admin/kas/pemasukan') }}
                                    class="sidebar-text flex items-center rounded-lg px-4 py-2.5 text-sm text-gray-600 transition-all hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600">
                                    <svg class="mr-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Pemasukan Masjid</span>
                                </a>
                            </li>
                            <li>
                                <a href={{ url('/admin/kas/pengeluaran') }}
                                    class="sidebar-text flex items-center rounded-lg px-4 py-2.5 text-sm text-gray-600 transition-all hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600">
                                    <svg class="mr-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>Pengeluaran Masjid</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Laporan Dropdown -->
                    <li>
                        <button onclick="toggleDropdown('laporanDropdown')"
                            class="flex w-full items-center justify-between rounded-lg px-4 py-3 text-gray-700 transition-all hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="sidebar-text ml-3 font-medium">Laporan</span>
                            </div>
                            <svg id="laporanDropdown-icon" class="sidebar-text h-4 w-4 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul id="laporanDropdown" class="ml-4 mt-2 hidden space-y-1">
                            <li>
                                <a href="{{ route('kas.index') }}"
                                    class="sidebar-text flex items-center rounded-lg px-4 py-2.5 text-sm text-gray-600 transition-all hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600">
                                    <svg class="mr-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    <span>Aktivitas Keuangan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('jurnal-umum.index') }}"
                                    class="sidebar-text flex items-center rounded-lg px-4 py-2.5 text-sm text-gray-600 transition-all hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600">
                                    <svg class="mr-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <span>Jurnal Umum</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('buku-besar.index') }}"
                                    class="sidebar-text flex items-center rounded-lg px-4 py-2.5 text-sm text-gray-600 transition-all hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600">
                                    <svg class="mr-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <span>Buku Besar</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>

<script>
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        const icon = document.getElementById(dropdownId + '-icon');

        dropdown.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }
</script>
