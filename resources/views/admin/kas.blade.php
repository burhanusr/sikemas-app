@extends('layouts.app')

@section('title', ucfirst($jenis) . ' Masjid')

@section('content')
    @php
        $isPemasukan = $jenis === 'pemasukan';
        $colorScheme = [
            'border' => $isPemasukan ? 'border-green-100' : 'border-orange-100',
            'divide' => $isPemasukan ? 'divide-green-100' : 'divide-orange-100',
            'bg' => $isPemasukan ? 'bg-green-500' : 'bg-orange-500',
            'bgHover' => $isPemasukan ? 'hover:bg-green-600' : 'hover:bg-orange-600',
            'bgGradientFrom' => $isPemasukan ? 'from-green-500' : 'from-orange-500',
            'bgGradientTo' => $isPemasukan ? 'to-emerald-600' : 'to-orange-600',
            'hoverGradientFrom' => $isPemasukan ? 'hover:from-green-600' : 'hover:from-orange-600',
            'hoverGradientTo' => $isPemasukan ? 'hover:to-emerald-700' : 'hover:to-orange-700',
            'text' => $isPemasukan ? 'text-green-600' : 'text-orange-600',
            'textLight' => $isPemasukan ? 'text-green-50' : 'text-orange-50',
            'badgeBgFrom' => $isPemasukan ? 'from-green-100' : 'from-orange-100',
            'badgeBgTo' => $isPemasukan ? 'to-emerald-100' : 'to-orange-100',
            'badgeText' => $isPemasukan ? 'text-green-700' : 'text-orange-700',
            'hoverBg' => $isPemasukan ? 'hover:bg-green-50/50' : 'hover:bg-orange-50/50',
            'ring' => $isPemasukan ? 'focus:ring-green-500' : 'focus:ring-orange-500',
        ];
    @endphp

    <style>
        @keyframes modalFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes modalFadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        @keyframes modalSlideOut {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            to {
                opacity: 0;
                transform: translateY(-50px) scale(0.95);
            }
        }

        .modal-overlay.show {
            animation: modalFadeIn 0.3s ease-out forwards;
        }

        .modal-overlay.hide {
            animation: modalFadeOut 0.3s ease-out forwards;
        }

        .modal-content.show {
            animation: modalSlideIn 0.3s ease-out forwards;
        }

        .modal-content.hide {
            animation: modalSlideOut 0.3s ease-out forwards;
        }
    </style>

    <div class="{{ $colorScheme['border'] }} rounded-xl border bg-white shadow-lg">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ ucfirst($jenis) }} Masjid</h2>
                    <p class="mt-1 text-gray-600">Kelola semua transaksi {{ $jenis }} keuangan masjid</p>
                </div>
                <button onclick="openCreateModal()"
                    class="{{ $colorScheme['bgGradientFrom'] }} {{ $colorScheme['bgGradientTo'] }} {{ $colorScheme['hoverGradientFrom'] }} {{ $colorScheme['hoverGradientTo'] }} inline-flex transform cursor-pointer items-center rounded-lg bg-gradient-to-r px-4 py-2.5 font-semibold text-white shadow-md transition-all hover:shadow-lg">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah {{ ucfirst($jenis) }}
                </button>
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

            @if (session('error'))
                <div
                    class="animate-fade-in flex items-center rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="animate-fade-in rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <div class="flex items-start">
                        <svg class="mr-3 mt-0.5 h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-semibold">Terdapat kesalahan:</p>
                            <ul class="mt-1 list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Table Card -->
            <div class="{{ $colorScheme['border'] }} rounded-xl border bg-white shadow-sm">
                <!-- Filter Section -->
                <div class="{{ $colorScheme['border'] }} border-b p-4">
                    <form method="GET" action="{{ url('/admin/kas/' . $jenis) }}" id="filterForm">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                            <!-- Search Input -->
                            <div class="flex-1">

                                <div class="relative">
                                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                        placeholder="Cari nama atau keterangan..."
                                        class="border-{{ $jenis === 'pemasukan' ? 'green' : 'orange' }}-200 focus:ring-{{ $jenis === 'pemasukan' ? 'green' : 'orange' }}-500 w-full rounded-lg border py-2 pl-10 pr-4 focus:outline-none focus:ring-2">
                                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Filter Kode Akun -->
                            <div class="w-full sm:w-64">
                                <x-autocomplete id="filterKodeAkun" name="kode_akun" placeholder="Pilih kode akun..."
                                    :color="$jenis === 'pemasukan' ? 'green' : 'orange'" size="small">
                                    <option value="">Semua Kode Akun</option>
                                    @foreach ($kodeAkun as $kode)
                                        <option value="{{ $kode->id }}"
                                            {{ request('kode_akun') == $kode->id ? 'selected' : '' }}>
                                            {{ $kode->kode_akun }} - {{ $kode->nama_akun }}
                                        </option>
                                    @endforeach
                                </x-autocomplete>
                            </div>

                            <!-- Filter Tanggal -->
                            <div class="w-full sm:w-48">
                                <x-input-date id="filterTanggal" name="tanggal" :color="$jenis === 'pemasukan' ? 'green' : 'orange'"
                                    value="{{ request('tanggal') }}" size="small" />
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-2">
                                @if (request()->hasAny(['search', 'kode_akun', 'tanggal']))
                                    <a href="{{ url('/admin/kas/' . $jenis) }}"
                                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                        <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Reset
                                    </a>
                                @endif

                                <button type="submit"
                                    class="{{ $colorScheme['bg'] }} {{ $colorScheme['bgHover'] }} {{ $colorScheme['ring'] }} inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2">
                                    <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Cari
                                </button>
                            </div>
                        </div>

                        <!-- Active Filters Badge -->
                        @if (request()->hasAny(['search', 'kode_akun', 'tanggal']))
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <span class="text-sm font-medium text-gray-600">Filter aktif:</span>

                                @if (request('search'))
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Pencarian: "{{ request('search') }}"
                                    </span>
                                @endif

                                @if (request('kode_akun'))
                                    @php
                                        $selectedKode = $kodeAkun->find(request('kode_akun'));
                                    @endphp
                                    @if ($selectedKode)
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                            </svg>
                                            Kode: {{ $selectedKode->kode_akun }}
                                        </span>
                                    @endif
                                @endif

                                @if (request('tanggal'))
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Tanggal: {{ \Carbon\Carbon::parse(request('tanggal'))->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="{{ $colorScheme['border'] }} border-b">
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    No</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Tanggal</th>
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
                        <tbody class="{{ $colorScheme['divide'] }} divide-y">
                            @forelse($kas as $index => $item)
                                <tr class="{{ $colorScheme['hoverBg'] }} transition-colors">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $kas->firstItem() + $index }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                        {{ $item->kodeAkun->nama_akun }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            class="{{ $colorScheme['badgeBgFrom'] }} {{ $colorScheme['badgeBgTo'] }} {{ $colorScheme['badgeText'] }} inline-flex items-center rounded-full bg-gradient-to-r px-3 py-1 text-xs font-semibold">
                                            {{ $item->kodeAkun->kode_akun ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($item->keterangan, 50) }}
                                    </td>
                                    <td
                                        class="{{ $colorScheme['text'] }} whitespace-nowrap px-6 py-4 text-sm font-semibold">
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
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
                                            <button onclick='openEditModal(@json($item))'
                                                class="inline-flex items-center rounded-lg bg-blue-500 px-3 py-1.5 text-sm font-medium text-white transition-all hover:bg-blue-600 hover:shadow-md">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button
                                                onclick="openDeleteModal({{ $item->id }}, '{{ $item->kodeAkun->nama_akun }}', '{{ number_format($item->nominal, 0, ',', '.') }}')"
                                                class="inline-flex items-center rounded-lg bg-red-500 px-3 py-1.5 text-sm font-medium text-white transition-all hover:bg-red-600 hover:shadow-md">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500">
                                            @if (request()->hasAny(['search', 'kode_akun', 'tanggal']))
                                                Tidak ada data yang sesuai dengan filter
                                            @else
                                                Belum ada data {{ $jenis }}
                                            @endif
                                        </p>
                                        <p class="mt-1 text-gray-400">
                                            @if (request()->hasAny(['search', 'kode_akun', 'tanggal']))
                                                Coba ubah atau reset filter pencarian
                                            @else
                                                Silakan tambahkan transaksi {{ $jenis }} baru
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

    <!-- Create Modal -->
    <div id="createModal"
        class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center bg-black/30 p-4 backdrop-blur-sm">
        <div class="modal-content w-full max-w-2xl transform rounded-xl bg-white shadow-2xl"
            onclick="event.stopPropagation()">
            <div
                class="{{ $colorScheme['bgGradientFrom'] }} {{ $colorScheme['bgGradientTo'] }} rounded-t-xl bg-gradient-to-r px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">Tambah {{ ucfirst($jenis) }} Baru</h3>
                        <p class="{{ $colorScheme['textLight'] }} mt-1">Masukkan detail transaksi {{ $jenis }}</p>
                    </div>
                    <button onclick="closeCreateModal()" class="text-white transition-colors hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <form action="{{ url('/admin/kas/' . $jenis) }}" method="POST" class="space-y-4 p-6">
                @csrf
                <input type="hidden" name="tipe" value="1">

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <x-input-date name="tanggal" label="Tanggal" required="true" :color="$jenis === 'pemasukan' ? 'green' : 'orange'"
                            placeholder="Pilih tanggal mulai" />
                    </div>

                    <div>
                        <x-autocomplete id="create_kodeakun_id" name="kodeakun_id" label="Kode Akun" :color="$jenis === 'pemasukan' ? 'green' : 'orange'"
                            required="true" placeholder="Pilih kode akun...">
                            @foreach ($kodeAkun as $kode)
                                <option value="{{ $kode->id }}">{{ $kode->kode_akun }} - {{ $kode->nama_akun }}
                                </option>
                            @endforeach
                        </x-autocomplete>
                    </div>

                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Nominal<span
                            class="text-red-500">*</span></label>
                    <input type="text" name="nominal" id="create_nominal" required placeholder="Rp 0"
                        class="focus:ring-{{ $jenis === 'pemasukan' ? 'green' : 'orange' }}-500 border-{{ $jenis === 'pemasukan' ? 'green' : 'orange' }}-300 w-full rounded-lg border px-4 py-3 focus:border-transparent focus:outline-none focus:ring-2">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Keterangan</label>
                    <textarea name="keterangan" rows="3" placeholder="Keterangan transaksi (opsional)"
                        class="focus:ring-{{ $jenis === 'pemasukan' ? 'green' : 'orange' }}-500 border-{{ $jenis === 'pemasukan' ? 'green' : 'orange' }}-300 w-full rounded-lg border px-4 py-3 focus:border-transparent focus:outline-none focus:ring-2"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="{{ $colorScheme['bgGradientFrom'] }} {{ $colorScheme['bgGradientTo'] }} {{ $colorScheme['hoverGradientFrom'] }} {{ $colorScheme['hoverGradientTo'] }} inline-flex flex-1 items-center justify-center rounded-lg bg-gradient-to-r px-6 py-3 font-semibold text-white shadow-md transition-all hover:shadow-lg">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan {{ ucfirst($jenis) }}
                    </button>
                    <button type="button" onclick="closeCreateModal()"
                        class="inline-flex flex-1 items-center justify-center rounded-lg border-2 border-gray-300 bg-white px-6 py-3 font-semibold text-gray-700 transition-all hover:bg-gray-50">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Edit Modal -->
    <div id="editModal"
        class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center bg-black/30 p-4 backdrop-blur-sm">
        <div class="modal-content w-full max-w-2xl transform rounded-xl bg-white shadow-2xl"
            onclick="event.stopPropagation()">
            <div class="rounded-t-xl bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">Edit {{ ucfirst($jenis) }}</h3>
                        <p class="mt-1 text-blue-50">Perbarui detail transaksi {{ $jenis }}</p>
                    </div>
                    <button onclick="closeEditModal()" class="text-white transition-colors hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <form id="editForm" method="POST" class="space-y-4 p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="tipe" value="1">

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <x-input-date id="edit_tanggal" name="tanggal" label="Tanggal" required="true"
                            placeholder="Pilih tanggal mulai" />
                    </div>

                    <div>
                        <x-autocomplete id="edit_kodeakun_id" name="kodeakun_id" label="Kode Akun" required="true"
                            placeholder="Pilih kode akun...">
                            @foreach ($kodeAkun as $kode)
                                <option value="{{ $kode->id }}">{{ $kode->kode_akun }} - {{ $kode->nama_akun }}
                                </option>
                            @endforeach
                        </x-autocomplete>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Nominal<span
                            class="text-red-500">*</span></label>
                    <input type="text" id="edit_nominal" name="nominal" required placeholder="Rp 0"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Keterangan</label>
                    <textarea id="edit_keterangan" name="keterangan" rows="3" placeholder="Keterangan transaksi (opsional)"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-3 font-semibold text-white shadow-md transition-all hover:from-blue-600 hover:to-blue-700 hover:shadow-lg">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update {{ ucfirst($jenis) }}
                    </button>
                    <button type="button" onclick="closeEditModal()"
                        class="inline-flex flex-1 items-center justify-center rounded-lg border-2 border-gray-300 bg-white px-6 py-3 font-semibold text-gray-700 transition-all hover:bg-gray-50">
                        Batal
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
                        <h3 class="text-xl font-bold text-white">Detail {{ ucfirst($jenis) }}</h3>
                        <p class="mt-1 text-purple-50">Informasi lengkap transaksi</p>
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

                    <!-- Date & Account Name -->
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                            <label class="mb-1 flex items-center gap-1.5 text-xs font-medium text-gray-500">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Tanggal
                            </label>
                            <p id="detail_tanggal" class="font-semibold text-gray-900"></p>
                        </div>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                            <label class="mb-1 flex items-center gap-1.5 text-xs font-medium text-gray-500">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Nama Akun
                            </label>
                            <p id="detail_nama" class="font-semibold text-gray-900"></p>
                        </div>
                    </div>

                    <!-- Account Code -->
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <label class="mb-1 flex items-center gap-1.5 text-xs font-medium text-gray-500">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                            Kode Akun
                        </label>
                        <p id="detail_kode_akun" class="font-mono font-semibold text-gray-900"></p>
                    </div>

                    <!-- Nominal & Saldo -->
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-lg border border-green-200 bg-green-50 p-3">
                            <label class="mb-1 flex items-center gap-1.5 text-xs font-medium text-green-700">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Nominal
                            </label>
                            <p id="detail_nominal" class="text-lg font-bold text-green-600"></p>
                        </div>

                        <div class="rounded-lg border border-blue-200 bg-blue-50 p-3">
                            <label class="mb-1 flex items-center gap-1.5 text-xs font-medium text-blue-700">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Saldo
                            </label>
                            <p id="detail_saldo" class="text-lg font-bold text-blue-600"></p>
                        </div>
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

    <!-- Delete Modal -->
    <div id="deleteModal"
        class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center bg-black/30 p-4 backdrop-blur-sm">
        <div class="modal-content w-full max-w-md transform rounded-xl bg-white shadow-2xl"
            onclick="event.stopPropagation()">
            <div class="rounded-t-xl bg-gradient-to-r from-red-500 to-red-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">Konfirmasi Hapus</h3>
                    <button onclick="closeDeleteModal()" class="text-white transition-colors hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Hapus {{ ucfirst($jenis) }}?</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Apakah Anda yakin ingin menghapus {{ $jenis }}
                        <span class="font-semibold" id="delete_nama_display"></span>
                        sebesar Rp <span class="font-semibold" id="delete_nominal_display"></span>?
                    </p>
                    <p class="mt-2 text-sm font-medium text-red-600">Tindakan ini tidak dapat dibatalkan!</p>
                </div>

                <form id="deleteForm" method="POST" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 rounded-lg border-2 border-gray-300 bg-white px-4 py-2.5 font-semibold text-gray-700 transition-all hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 rounded-lg bg-gradient-to-r from-red-500 to-red-600 px-4 py-2.5 font-semibold text-white shadow-md transition-all hover:from-red-600 hover:to-red-700 hover:shadow-lg">
                            Hapus
                        </button>
                    </div>
                </form>
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

                    // Set display text
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

            // Submit form on Enter key press in search input (optional - you can remove this if you don't want it)
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('filterForm').submit();
                    }
                });
            }

            // Format Rupiah Function
            function formatRupiahInput(value) {
                let numberString = value.replace(/\D/g, '');
                let formatted = new Intl.NumberFormat('id-ID').format(numberString);
                return formatted ? 'Rp ' + formatted : '';
            }

            // Create Modal - Nominal Input Formatting
            const createNominalInput = document.getElementById('create_nominal');
            if (createNominalInput) {
                createNominalInput.addEventListener('input', function(e) {
                    const cursorPos = this.selectionStart;
                    const originalLength = this.value.length;
                    this.value = formatRupiahInput(this.value);
                    const newLength = this.value.length;
                    this.selectionEnd = cursorPos + (newLength - originalLength);
                });
            }

            // Edit Modal - Nominal Input Formatting
            const editNominalInput = document.getElementById('edit_nominal');
            if (editNominalInput) {
                editNominalInput.addEventListener('input', function(e) {
                    const cursorPos = this.selectionStart;
                    const originalLength = this.value.length;
                    this.value = formatRupiahInput(this.value);
                    const newLength = this.value.length;
                    this.selectionEnd = cursorPos + (newLength - originalLength);
                });
            }

            // Remove format before form submission
            const createForm = document.querySelector('#createModal form');
            if (createForm && createNominalInput) {
                createForm.addEventListener('submit', function() {
                    createNominalInput.value = createNominalInput.value.replace(/\D/g, '');
                });
            }

            const editForm = document.getElementById('editForm');
            if (editForm && editNominalInput) {
                editForm.addEventListener('submit', function() {
                    editNominalInput.value = editNominalInput.value.replace(/\D/g, '');
                });
            }

            // Close modal when clicking overlay
            ['createModal', 'editModal', 'detailModal', 'deleteModal'].forEach(id => {
                const modal = document.getElementById(id);
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            if (id === 'createModal') closeCreateModal();
                            if (id === 'editModal') closeEditModal();
                            if (id === 'detailModal') closeDetailModal();
                            if (id === 'deleteModal') closeDeleteModal();
                        }
                    });
                }
            });

            // Close modal with ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeCreateModal();
                    closeEditModal();
                    closeDetailModal();
                    closeDeleteModal();
                }
            });
        });

        // Modal functions...
        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        function formatRupiahInput(value) {
            let number = Math.floor(parseFloat(value) || 0);
            return number ? 'Rp ' + new Intl.NumberFormat('id-ID').format(number) : '';
        }

        function openCreateModal() {
            const modal = document.getElementById('createModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeCreateModal() {
            const modal = document.getElementById('createModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function openEditModal(item) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            form.action = `/admin/kas/${item.id}`;

            const d = new Date(item.tanggal);
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            document.getElementById('edit_tanggal').value = `${yyyy}-${mm}-${dd}`;

            document.getElementById('edit_nominal').value = formatRupiahInput(item.nominal.toString());
            document.getElementById('edit_keterangan').value = item.keterangan || '';

            const editKodeAkunId = 'edit_kodeakun_id';
            const hiddenInput = document.getElementById(editKodeAkunId + '-value');
            const visibleInput = document.getElementById(editKodeAkunId);

            if (hiddenInput && visibleInput && item.kode_akun) {
                hiddenInput.value = item.kodeakun_id;
                visibleInput.value = item.kode_akun.kode_akun + ' - ' + item.kode_akun.nama_akun;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function openDetailModal(item) {
            document.getElementById('detail_tanggal').textContent = new Date(item.tanggal).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            document.getElementById('detail_nama').textContent = item.kode_akun.nama_akun;
            document.getElementById('detail_kode_akun').textContent = item.kode_akun ?
                `${item.kode_akun.kode_akun} - ${item.kode_akun.nama_akun}` : '-';
            document.getElementById('detail_nominal').textContent = formatRupiah(item.nominal);
            document.getElementById('detail_saldo').textContent = formatRupiah(item.saldo);
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

        function openDeleteModal(id, nama, nominal) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = `/admin/kas/${id}`;

            document.getElementById('delete_nama_display').textContent = nama;
            document.getElementById('delete_nominal_display').textContent = nominal;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
