@extends('layouts.app')

@section('title', 'Master Data Kode Akun')

@section('content')
    <style>
        /* Modal Animation */
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

    <!-- Main Container Box -->
    <div class="rounded-xl border border-green-100 bg-white shadow-lg">
        <div class="space-y-6 p-6">
            <!-- Header Section -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Master Data Kode Akun</h2>
                    <p class="mt-1 text-gray-600">Kelola kode akun untuk pencatatan keuangan masjid</p>
                </div>
                <button onclick="openCreateModal()"
                    class="inline-flex transform cursor-pointer items-center rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 px-4 py-2.5 font-semibold text-white shadow-md transition-all hover:from-green-600 hover:to-emerald-700 hover:shadow-lg">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Kode Akun
                </button>
            </div>

            <!-- Success Alert -->
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
            <div class="overflow-hidden rounded-xl border border-green-100 bg-white shadow-sm">
                <!-- Search & Filter Section -->
                <div class="border-b border-green-100 p-4">
                    <div class="flex flex-col gap-4 sm:flex-row">
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" id="searchInput" placeholder="Cari kode atau nama akun..."
                                    class="w-full rounded-lg border border-green-200 py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <select id="filterKategori"
                            class="rounded-lg border border-green-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Kategori</option>
                            <option value="Saldo">Saldo</option>
                            <option value="Pemasukan">Pemasukan</option>
                            <option value="Pengeluaran">Pengeluaran</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    No
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Kode Akun</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Nama Akun</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Kategori</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Tanggal Dibuat</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-green-100">
                            @forelse($kodeAkun as $index => $item)
                                <tr class="transition-colors hover:bg-green-50/50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $kodeAkun->firstItem() + $index }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-full bg-gradient-to-r from-green-100 to-emerald-100 px-3 py-1 text-sm font-semibold text-green-700">
                                            {{ $item->kode_akun }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                        {{ $item->nama_akun }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @php
                                            $colors = [
                                                'Saldo' => 'bg-blue-100 text-blue-700',
                                                'Pemasukan' => 'bg-green-100 text-green-700',
                                                'Pengeluaran' => 'bg-red-100 text-red-700',
                                            ];
                                            $color = $colors[$item->kategori_akun] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span
                                            class="{{ $color }} inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
                                            {{ $item->kategori_akun }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $item->created_at->format('d M Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick='openEditModal(@json($item))'
                                                class="inline-flex items-center rounded-lg bg-blue-500 px-3 py-1.5 text-sm font-medium text-white transition-all hover:bg-blue-600 hover:shadow-md">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button
                                                onclick="openDeleteModal({{ $item->id }}, '{{ $item->kode_akun }}', '{{ $item->nama_akun }}')"
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
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500">Belum ada data kode akun</p>
                                        <p class="mt-1 text-gray-400">Silakan tambahkan kode akun baru</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($kodeAkun->hasPages())
                    <div class="mt-6 rounded-xl border border-gray-100 bg-white p-4 shadow-sm">
                        {{ $kodeAkun->links('components.pagination') }}
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
            <!-- Modal Header -->
            <div class="rounded-t-xl bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">Tambah Kode Akun Baru</h3>
                        <p class="mt-1 text-green-50">Masukkan informasi kode akun untuk pencatatan keuangan</p>
                    </div>
                    <button onclick="closeCreateModal()" class="text-white transition-colors hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('kode-akun.store') }}" method="POST" class="space-y-4 p-6">
                @csrf

                <!-- Kode Akun -->
                <div>
                    <label for="create_kode_akun" class="mb-2 block text-sm font-semibold text-gray-700">
                        Kode Akun <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="create_kode_akun" name="kode_akun" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Contoh: 1-1000">
                    <p class="mt-2 text-xs text-gray-500">Format: [Kategori]-[Nomor], contoh: 1-1000 untuk Kas</p>
                </div>

                <!-- Nama Akun -->
                <div>
                    <label for="create_nama_akun" class="mb-2 block text-sm font-semibold text-gray-700">
                        Nama Akun <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="create_nama_akun" name="nama_akun" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Contoh: Kas">
                </div>

                <!-- Kategori Akun -->
                <div>
                    <label for="create_kategori_akun" class="mb-2 block text-sm font-semibold text-gray-700">
                        Kategori Akun <span class="text-red-500">*</span>
                    </label>
                    <select id="create_kategori_akun" name="kategori_akun" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Pilih Kategori</option>
                        <option value="Saldo">Saldo</option>
                        <option value="Pemasukan">Pemasukan</option>
                        <option value="Pengeluaran">Pengeluaran</option>
                    </select>
                </div>

                <!-- Info Box -->
                <div class="rounded-lg border border-blue-200 bg-gradient-to-r from-blue-50 to-blue-100 p-4">
                    <div class="flex items-start">
                        <svg class="mr-3 mt-0.5 h-5 w-5 flex-shrink-0 text-blue-600" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h4 class="mb-1 text-sm font-semibold text-blue-900">Panduan Kategori Akun</h4>
                            <ul class="space-y-1 text-sm text-blue-800">
                                <li><strong>Saldo:</strong> Menunjukkan posisi keuangan saat ini (Kas, Bank, dll)</li>
                                <li><strong>Pemasukan:</strong> Semua uang yang masuk (Infaq, Donasi, dll)</li>
                                <li><strong>Pengeluaran:</strong> Semua uang yang keluar (Operasional, Perbaikan, dll)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-3 font-semibold text-white shadow-md transition-all hover:from-green-600 hover:to-emerald-700 hover:shadow-lg">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Kode Akun
                    </button>
                    <button type="button" onclick="closeCreateModal()"
                        class="inline-flex flex-1 items-center justify-center rounded-lg border-2 border-gray-300 bg-white px-6 py-3 font-semibold text-gray-700 transition-all hover:bg-gray-50">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
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
            <!-- Modal Header -->
            <div class="rounded-t-xl bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">Edit Kode Akun</h3>
                        <p class="mt-1 text-blue-50">Perbarui informasi kode akun</p>
                    </div>
                    <button onclick="closeEditModal()" class="text-white transition-colors hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <form id="editForm" method="POST" class="space-y-4 p-6">
                @csrf
                @method('PUT')

                <!-- Kode Akun -->
                <div>
                    <label for="edit_kode_akun" class="mb-2 block text-sm font-semibold text-gray-700">
                        Kode Akun <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="edit_kode_akun" name="kode_akun" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Contoh: 1-1000">
                    <p class="mt-2 text-xs text-gray-500">Format: [Kategori]-[Nomor], contoh: 1-1000 untuk Kas</p>
                </div>

                <!-- Nama Akun -->
                <div>
                    <label for="edit_nama_akun" class="mb-2 block text-sm font-semibold text-gray-700">
                        Nama Akun <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="edit_nama_akun" name="nama_akun" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Contoh: Kas">
                </div>

                <!-- Kategori Akun -->
                <div>
                    <label for="edit_kategori_akun" class="mb-2 block text-sm font-semibold text-gray-700">
                        Kategori Akun <span class="text-red-500">*</span>
                    </label>
                    <select id="edit_kategori_akun" name="kategori_akun" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Kategori</option>
                        <option value="Saldo">Saldo</option>
                        <option value="Pemasukan">Pemasukan</option>
                        <option value="Pengeluaran">Pengeluaran</option>
                    </select>
                </div>

                <!-- Warning Box -->
                <div class="rounded-lg border border-amber-200 bg-gradient-to-r from-amber-50 to-amber-100 p-4">
                    <div class="flex items-start">
                        <svg class="mr-3 mt-0.5 h-5 w-5 flex-shrink-0 text-amber-600" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h4 class="mb-1 text-sm font-semibold text-amber-900">Perhatian!</h4>
                            <p class="text-sm text-amber-800">Pastikan perubahan kode akun tidak mempengaruhi transaksi
                                yang sudah tercatat.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-3 font-semibold text-white shadow-md transition-all hover:from-blue-600 hover:to-blue-700 hover:shadow-lg">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Kode Akun
                    </button>
                    <button type="button" onclick="closeEditModal()"
                        class="inline-flex flex-1 items-center justify-center rounded-lg border-2 border-gray-300 bg-white px-6 py-3 font-semibold text-gray-700 transition-all hover:bg-gray-50">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal"
        class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center bg-black/30 p-4 backdrop-blur-sm">
        <div class="modal-content w-full max-w-md transform rounded-xl bg-white shadow-2xl"
            onclick="event.stopPropagation()">
            <!-- Modal Header -->
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

            <!-- Modal Body -->
            <div class="p-6">
                <div class="text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Hapus Kode Akun?</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Apakah Anda yakin ingin menghapus kode akun
                        <span class="font-semibold" id="delete_kode_display"></span> -
                        <span class="font-semibold" id="delete_nama_display"></span>?
                    </p>
                    <p class="mt-2 text-sm font-medium text-red-600">
                        Tindakan ini tidak dapat dibatalkan!
                    </p>
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
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });

        // Filter by category
        document.getElementById('filterKategori').addEventListener('change', function() {
            const filterValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                if (filterValue === '') {
                    row.style.display = '';
                } else {
                    const kategori = row.cells[3]?.textContent.toLowerCase().trim();
                    row.style.display = kategori === filterValue ? '' : 'none';
                }
            });
        });

        // Create Modal Functions
        function openCreateModal() {
            const modal = document.getElementById('createModal');
            const content = modal.querySelector('.modal-content');

            modal.classList.remove('hidden');
            modal.classList.add('flex', 'show');
            content.classList.add('show');
            document.body.style.overflow = 'hidden';

            // Remove show class after animation
            setTimeout(() => {
                modal.classList.remove('show');
                content.classList.remove('show');
            }, 300);
        }

        function closeCreateModal() {
            const modal = document.getElementById('createModal');
            const content = modal.querySelector('.modal-content');

            modal.classList.add('hide');
            content.classList.add('hide');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'hide');
                content.classList.remove('hide');
                document.body.style.overflow = 'auto';

                // Reset form
                document.getElementById('create_kode_akun').value = '';
                document.getElementById('create_nama_akun').value = '';
                document.getElementById('create_kategori_akun').value = '';
            }, 300);
        }

        // Edit Modal Functions
        function openEditModal(item) {
            const modal = document.getElementById('editModal');
            const content = modal.querySelector('.modal-content');
            const form = document.getElementById('editForm');

            // Set form action with proper route
            form.action = "{{ url('admin/kode-akun') }}/" + item.id;

            // Populate form fields
            document.getElementById('edit_kode_akun').value = item.kode_akun;
            document.getElementById('edit_nama_akun').value = item.nama_akun;
            document.getElementById('edit_kategori_akun').value = item.kategori_akun;

            // Show modal with animation
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'show');
            content.classList.add('show');
            document.body.style.overflow = 'hidden';

            // Remove show class after animation
            setTimeout(() => {
                modal.classList.remove('show');
                content.classList.remove('show');
            }, 300);
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            const content = modal.querySelector('.modal-content');

            modal.classList.add('hide');
            content.classList.add('hide');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'hide');
                content.classList.remove('hide');
                document.body.style.overflow = 'auto';
            }, 300);
        }

        // Delete Modal Functions
        function openDeleteModal(id, kode, nama) {
            const modal = document.getElementById('deleteModal');
            const content = modal.querySelector('.modal-content');
            const form = document.getElementById('deleteForm');

            // Set form action with proper route
            form.action = "{{ url('admin/kode-akun') }}/" + id;

            // Set display text
            document.getElementById('delete_kode_display').textContent = kode;
            document.getElementById('delete_nama_display').textContent = nama;

            // Show modal with animation
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'show');
            content.classList.add('show');
            document.body.style.overflow = 'hidden';

            // Remove show class after animation
            setTimeout(() => {
                modal.classList.remove('show');
                content.classList.remove('show');
            }, 300);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const content = modal.querySelector('.modal-content');

            modal.classList.add('hide');
            content.classList.add('hide');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'hide');
                content.classList.remove('hide');
                document.body.style.overflow = 'auto';
            }, 300);
        }

        // Close modals when clicking outside
        document.getElementById('createModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateModal();
            }
        });

        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateModal();
                closeEditModal();
                closeDeleteModal();
            }
        });
    </script>
@endsection
