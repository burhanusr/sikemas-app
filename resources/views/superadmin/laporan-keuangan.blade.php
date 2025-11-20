@extends('layouts.app')

@section('title', 'Laporan Keuangan Masjid')

@section('content')
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


    <div class="rounded-xl border border-emerald-100 bg-white shadow-lg">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Laporan Keuangan Masjid</h2>
                    <p class="mt-1 text-gray-600">Daftar seluruh masjid dan akses cepat ke informasi keuangan</p>
                </div>
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2">
                    <p class="text-sm font-semibold text-emerald-800">
                        Total Masjid: <span class="text-lg">{{ $mosques->total() }}</span>
                    </p>
                </div>
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
            <div class="rounded-xl border border-emerald-100 bg-white shadow-sm">
                <!-- Filter Section -->
                <div class="border-b border-emerald-100 p-4">
                    <form method="GET" action="{{ route('superadmin.laporan-keuangan') }}" id="filterForm">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
                            <!-- Search Input -->
                            <div class="flex-1">
                                <label class="mb-2 block text-sm font-semibold text-gray-700">Cari</label>
                                <div class="relative">
                                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                        placeholder="Cari nama masjid atau admin..."
                                        class="w-full rounded-lg border border-emerald-200 py-2 pl-10 pr-4 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-2">
                                @if (request('search'))
                                    <a href="{{ route('superadmin.laporan-keuangan') }}"
                                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                        <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Reset
                                    </a>
                                @endif

                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-lg bg-emerald-500 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                    <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Cari
                                </button>
                            </div>
                        </div>

                        <!-- Active Filters Badge -->
                        @if (request('search'))
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <span class="text-sm font-medium text-gray-600">Filter aktif:</span>
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Pencarian: "{{ request('search') }}"
                                </span>
                            </div>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-emerald-100 bg-emerald-50">
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    No</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Nama Masjid</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Admin</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Saldo Kas</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-100">
                            @forelse($mosques as $index => $mosque)
                                <tr class="transition-colors hover:bg-emerald-50/50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $mosques->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-gray-800">{{ $mosque->organization }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $mosque->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $mosque->email }}</p>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <span
                                            class="{{ $mosque->saldo >= 0 ? 'text-green-600' : 'text-red-600' }} text-sm font-bold">
                                            Rp {{ number_format(abs($mosque->saldo ?? 0), 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Aktivitas Keuangan -->
                                            <a href="{{ route('kas.index', ['user_id' => $mosque->id]) }}"
                                                class="group relative rounded-lg bg-blue-50 p-2 transition-all hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                title="Aktivitas Keuangan">
                                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span
                                                    class="absolute -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition-opacity group-hover:opacity-100">
                                                    Aktivitas Keuangan
                                                </span>
                                            </a>

                                            <!-- Jurnal Umum -->
                                            <a href="{{ route('jurnal-umum.index', ['user_id' => $mosque->id]) }}"
                                                class="group relative rounded-lg bg-indigo-50 p-2 transition-all hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                title="Jurnal Umum">
                                                <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span
                                                    class="absolute -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition-opacity group-hover:opacity-100">
                                                    Jurnal Umum
                                                </span>
                                            </a>

                                            <!-- Buku Besar -->
                                            <a href="{{ route('buku-besar.index', ['user_id' => $mosque->id]) }}"
                                                class="group relative rounded-lg bg-purple-50 p-2 transition-all hover:bg-purple-100 focus:outline-none focus:ring-2 focus:ring-purple-500"
                                                title="Buku Besar">
                                                <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                                <span
                                                    class="absolute -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition-opacity group-hover:opacity-100">
                                                    Buku Besar
                                                </span>
                                            </a>

                                            <!-- Diskusi -->
                                            <button
                                                onclick="openDiscussion({{ $mosque->id }}, '{{ $mosque->organization }}', '{{ $mosque->name }}')"
                                                class="group relative rounded-lg bg-amber-50 p-2 transition-all hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                                title="Diskusi">
                                                <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                                <span id="unread-badge-{{ $mosque->id }}"
                                                    class="absolute -right-1 -top-1 hidden h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white"></span>
                                                <span
                                                    class="absolute -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition-opacity group-hover:opacity-100">
                                                    Diskusi
                                                </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500">
                                            @if (request('search'))
                                                Tidak ada masjid yang sesuai dengan pencarian
                                            @else
                                                Belum ada data masjid
                                            @endif
                                        </p>
                                        <p class="mt-1 text-gray-400">
                                            @if (request('search'))
                                                Coba ubah kata kunci pencarian
                                            @else
                                                Data masjid akan muncul ketika admin mendaftar
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($mosques->hasPages())
                    <div class="mt-6 rounded-xl border border-gray-100 bg-white p-4 shadow-sm">
                        {{ $mosques->links('components.pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal"
        class="modal-overlay fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 p-4 backdrop-blur-sm">
        <div class="modal-content w-full max-w-md rounded-xl bg-white shadow-2xl">
            <div class="p-6">
                <div class="mb-4 flex items-center justify-center">
                    <div class="rounded-full bg-amber-100 p-3">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="mb-2 text-center text-lg font-bold text-gray-800" id="confirmTitle">Konfirmasi</h3>
                <p class="mb-6 text-center text-gray-600" id="confirmMessage">Apakah Anda yakin?</p>
                <div class="flex gap-3">
                    <button onclick="closeConfirmModal()"
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2 font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button onclick="confirmAction()" id="confirmButton"
                        class="flex-1 rounded-lg bg-amber-500 px-4 py-2 font-medium text-white hover:bg-amber-600">
                        Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal"
        class="modal-overlay fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 p-4 backdrop-blur-sm">
        <div class="modal-content w-full max-w-md rounded-xl bg-white shadow-2xl">
            <div class="p-6">
                <div class="mb-4 flex items-center justify-center">
                    <div class="rounded-full bg-green-100 p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <h3 class="mb-2 text-center text-lg font-bold text-gray-800">Berhasil</h3>
                <p class="mb-6 text-center text-gray-600" id="successMessage">Operasi berhasil dilakukan</p>
                <button onclick="closeSuccessModal()"
                    class="w-full rounded-lg bg-green-500 px-4 py-2 font-medium text-white hover:bg-green-600">
                    OK
                </button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal"
        class="modal-overlay fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 p-4 backdrop-blur-sm">
        <div class="modal-content w-full max-w-md rounded-xl bg-white shadow-2xl">
            <div class="p-6">
                <div class="mb-4 flex items-center justify-center">
                    <div class="rounded-full bg-red-100 p-3">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
                <h3 class="mb-2 text-center text-lg font-bold text-gray-800">Terjadi Kesalahan</h3>
                <p class="mb-6 text-center text-gray-600" id="errorMessage">Terjadi kesalahan saat memproses permintaan
                </p>
                <button onclick="closeErrorModal()"
                    class="w-full rounded-lg bg-red-500 px-4 py-2 font-medium text-white hover:bg-red-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Discussion Modal -->
    <div id="discussionModal"
        class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center bg-black/30 p-4 backdrop-blur-sm">
        <div class="modal-content w-full max-w-4xl rounded-xl bg-white shadow-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-gray-200 p-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800" id="modalMosqueName"></h3>
                    <p class="text-sm text-gray-600" id="modalAdminName"></p>
                </div>
                <button onclick="closeDiscussion()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Discussions List / Chat View -->
            <div class="flex h-[600px]">
                <!-- Left Sidebar - Discussion List -->
                <div class="w-80 rounded-bl-xl border-r border-gray-200 bg-gray-50">
                    <div class="p-4">
                        <button onclick="showNewDiscussionForm()"
                            class="w-full rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white hover:bg-amber-600">
                            <svg class="mr-2 inline-block h-4 w-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Diskusi Baru
                        </button>
                    </div>
                    <div id="discussionsList" class="overflow-y-auto" style="height: calc(100% - 80px);">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Right Side - Chat Area -->
                <div class="flex flex-1 flex-col">
                    <!-- New Discussion Form -->
                    <div id="newDiscussionForm" class="hidden flex-1 flex-col p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-800">Buat Diskusi Baru</h4>
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Subjek</label>
                            <input type="text" id="discussionSubject"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                placeholder="Masukkan subjek diskusi...">
                        </div>
                        <div class="mb-4 flex-1">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Pesan</label>
                            <textarea id="discussionMessage" rows="10"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                placeholder="Tulis pesan Anda..."></textarea>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="createDiscussion()"
                                class="rounded-lg bg-amber-500 px-6 py-2 text-white hover:bg-amber-600">
                                Kirim
                            </button>
                            <button onclick="hideNewDiscussionForm()"
                                class="rounded-lg border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50">
                                Batal
                            </button>
                        </div>
                    </div>

                    <!-- Chat View -->
                    <div id="chatView" class="hidden flex-1 flex-col">
                        <!-- Messages Container -->
                        <div id="messagesContainer" class="flex-1 overflow-y-auto p-6">
                            <!-- Will be populated by JavaScript -->
                        </div>

                        <!-- Message Input -->
                        <div class="border-t border-gray-200 p-4">
                            <div class="flex gap-2">
                                <input type="text" id="messageInput"
                                    class="flex-1 rounded-lg border border-gray-300 px-4 py-2 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    placeholder="Ketik pesan..." onkeypress="handleMessageKeyPress(event)">
                                <button onclick="sendMessage()"
                                    class="rounded-lg bg-amber-500 px-6 py-2 text-white hover:bg-amber-600">
                                    Kirim
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="flex flex-1 items-center justify-center p-6">
                        <div class="text-center">
                            <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="text-gray-500">Pilih diskusi atau buat diskusi baru</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentAdminId = null;
        let currentDiscussionId = null;
        let lastMessageId = 0;
        let pollingInterval = null;
        let userId = {{ Auth::id() }};
        let confirmCallback = null;

        // Submit form on Enter key press in search input
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('filterForm').submit();
                    }
                });
            }

            // Load unread counts on page load
            loadUnreadCounts();

            // Refresh unread counts every 30 seconds
            setInterval(loadUnreadCounts, 30000);
        });

        // Modal Functions
        function showConfirmModal(title, message, callback) {
            document.getElementById('confirmTitle').textContent = title;
            document.getElementById('confirmMessage').textContent = message;
            confirmCallback = callback;
            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmModal').classList.add('flex');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            document.getElementById('confirmModal').classList.remove('flex');
            confirmCallback = null;
        }

        function confirmAction() {
            if (confirmCallback) {
                confirmCallback();
            }
            closeConfirmModal();
        }

        function showSuccessModal(message) {
            document.getElementById('successMessage').textContent = message;
            document.getElementById('successModal').classList.remove('hidden');
            document.getElementById('successModal').classList.add('flex');
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
            document.getElementById('successModal').classList.remove('flex');
        }

        function showErrorModal(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorModal').classList.remove('hidden');
            document.getElementById('errorModal').classList.add('flex');
        }

        function closeErrorModal() {
            document.getElementById('errorModal').classList.add('hidden');
            document.getElementById('errorModal').classList.remove('flex');
        }

        function openDiscussion(adminId, mosqueName, adminName) {
            // Reset state when switching admins
            if (currentAdminId !== adminId) {
                currentDiscussionId = null;
                lastMessageId = 0;
                stopPolling();

                // Reset view to empty state
                document.getElementById('emptyState').classList.remove('hidden');
                document.getElementById('chatView').classList.add('hidden');
                document.getElementById('newDiscussionForm').classList.add('hidden');
                document.getElementById('messagesContainer').innerHTML = '';
                document.getElementById('discussionsList').innerHTML = '';
            }

            currentAdminId = adminId;
            document.getElementById('modalMosqueName').textContent = mosqueName;
            document.getElementById('modalAdminName').textContent = `Admin: ${adminName}`;
            document.getElementById('discussionModal').classList.remove('hidden');
            document.getElementById('discussionModal').classList.add('flex');

            loadDiscussions();
        }

        function closeDiscussion() {
            document.getElementById('discussionModal').classList.add('hidden');
            document.getElementById('discussionModal').classList.remove('flex');
            currentAdminId = null;
            currentDiscussionId = null;
            stopPolling();
        }

        async function loadDiscussions() {
            try {
                const response = await fetch(`/discussions?admin_id=${currentAdminId}`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const discussions = await response.json();

                const listHtml = discussions.map(d => `
                    <div onclick="selectDiscussion(${d.id})" class="cursor-pointer border-b border-gray-200 p-4 transition-colors hover:bg-gray-100 ${currentDiscussionId === d.id ? 'bg-amber-50' : ''}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h5 class="font-semibold text-gray-800">${d.subject}</h5>
                                <p class="text-xs text-gray-500">${formatDate(d.last_message_at || d.created_at)}</p>
                            </div>
                            ${d.unread_count > 0 ? `<span class="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white">${d.unread_count}</span>` : ''}
                        </div>
                        ${d.latest_message ? `<p class="mt-1 truncate text-sm text-gray-600">${d.latest_message.message}</p>` : ''}
                        <span class="mt-1 inline-block rounded px-2 py-0.5 text-xs ${d.status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">${d.status === 'open' ? 'Aktif' : 'Ditutup'}</span>
                    </div>
                `).join('');

                document.getElementById('discussionsList').innerHTML = listHtml ||
                    '<p class="p-4 text-center text-sm text-gray-500">Belum ada diskusi</p>';
            } catch (error) {
                console.error('Error loading discussions:', error);
            }
        }

        function showNewDiscussionForm() {
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('chatView').classList.add('hidden');
            document.getElementById('newDiscussionForm').classList.remove('hidden');
            document.getElementById('newDiscussionForm').classList.add('flex');
        }

        function hideNewDiscussionForm() {
            document.getElementById('newDiscussionForm').classList.add('hidden');
            document.getElementById('newDiscussionForm').classList.remove('flex');
            document.getElementById('emptyState').classList.remove('hidden');
            document.getElementById('discussionSubject').value = '';
            document.getElementById('discussionMessage').value = '';
        }

        async function createDiscussion() {
            const subject = document.getElementById('discussionSubject').value.trim();
            const message = document.getElementById('discussionMessage').value.trim();

            if (!subject || !message) {
                showErrorModal('Subjek dan pesan harus diisi');
                return;
            }

            try {
                const response = await fetch('/discussions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        admin_id: currentAdminId,
                        subject: subject,
                        message: message
                    })
                });

                if (response.ok) {
                    const discussion = await response.json();
                    hideNewDiscussionForm();
                    await loadDiscussions();
                    selectDiscussion(discussion.id);
                    showSuccessModal('Diskusi berhasil dibuat');
                } else {
                    showErrorModal('Gagal membuat diskusi');
                }
            } catch (error) {
                console.error('Error creating discussion:', error);
                showErrorModal('Terjadi kesalahan saat membuat diskusi');
            }
        }

        async function selectDiscussion(discussionId) {
            currentDiscussionId = discussionId;
            lastMessageId = 0;

            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('newDiscussionForm').classList.add('hidden');
            document.getElementById('newDiscussionForm').classList.remove('flex');
            document.getElementById('chatView').classList.remove('hidden');
            document.getElementById('chatView').classList.add('flex');

            await loadMessages();
            await loadDiscussions(); // Refresh list to update unread count

            stopPolling();
            startPolling();
        }

        async function loadMessages() {
            try {
                const response = await fetch(`/discussions/${currentDiscussionId}`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const discussion = await response.json();

                const messagesHtml = discussion.messages.map(m => `
                    <div class="mb-4 flex ${m.user_id === userId ? 'justify-end' : 'justify-start'}">
                        <div class="max-w-[70%]">
                            <div class="mb-1 flex items-center gap-2 ${m.user_id === userId ? 'flex-row-reverse' : ''}">
                                <span class="text-xs font-semibold text-gray-700">${m.user.name}</span>
                                <span class="text-xs text-gray-400">${formatTime(m.created_at)}</span>
                            </div>
                            <div class="rounded-lg px-4 py-2 ${m.user_id === userId ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-800'}">
                                ${escapeHtml(m.message)}
                            </div>
                        </div>
                    </div>
                `).join('');

                document.getElementById('messagesContainer').innerHTML = messagesHtml;
                scrollToBottom();

                if (discussion.messages.length > 0) {
                    lastMessageId = discussion.messages[discussion.messages.length - 1].id;
                }
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        }

        async function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();

            if (!message || !currentDiscussionId) return;

            try {
                const response = await fetch(`/discussions/${currentDiscussionId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message
                    })
                });

                if (response.ok) {
                    messageInput.value = '';
                    await loadMessages();
                    await loadDiscussions();
                } else {
                    showErrorModal('Gagal mengirim pesan');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                showErrorModal('Terjadi kesalahan saat mengirim pesan');
            }
        }

        function handleMessageKeyPress(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage();
            }
        }

        function startPolling() {
            pollingInterval = setInterval(async () => {
                if (!currentDiscussionId) return;

                try {
                    const response = await fetch(
                        `/discussions/${currentDiscussionId}/new-messages?last_message_id=${lastMessageId}`, {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                    const newMessages = await response.json();

                    if (newMessages.length > 0) {
                        const container = document.getElementById('messagesContainer');
                        const messagesHtml = newMessages.map(m => `
                            <div class="mb-4 flex ${m.user_id === userId ? 'justify-end' : 'justify-start'}">
                                <div class="max-w-[70%]">
                                    <div class="mb-1 flex items-center gap-2 ${m.user_id === userId ? 'flex-row-reverse' : ''}">
                                        <span class="text-xs font-semibold text-gray-700">${m.user.name}</span>
                                        <span class="text-xs text-gray-400">${formatTime(m.created_at)}</span>
                                    </div>
                                    <div class="rounded-lg px-4 py-2 ${m.user_id === userId ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-800'}">
                                        ${escapeHtml(m.message)}
                                    </div>
                                </div>
                            </div>
                        `).join('');

                        container.insertAdjacentHTML('beforeend', messagesHtml);
                        scrollToBottom();

                        lastMessageId = newMessages[newMessages.length - 1].id;
                        await loadDiscussions();
                    }
                } catch (error) {
                    console.error('Error polling messages:', error);
                }
            }, 3000); // Poll every 3 seconds
        }

        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }

        async function loadUnreadCounts() {
            try {
                const response = await fetch('/discussions', {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const allDiscussions = await response.json();

                // Group by admin_id and count unread
                const unreadByAdmin = {};
                allDiscussions.forEach(d => {
                    if (!unreadByAdmin[d.admin_id]) {
                        unreadByAdmin[d.admin_id] = 0;
                    }
                    unreadByAdmin[d.admin_id] += d.unread_count || 0;
                });

                // Update badges
                Object.keys(unreadByAdmin).forEach(adminId => {
                    const badge = document.getElementById(`unread-badge-${adminId}`);
                    if (badge) {
                        const count = unreadByAdmin[adminId];
                        if (count > 0) {
                            badge.textContent = count > 9 ? '9+' : count;
                            badge.classList.remove('hidden');
                            badge.classList.add('flex');
                        } else {
                            badge.classList.add('hidden');
                            badge.classList.remove('flex');
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading unread counts:', error);
            }
        }

        function scrollToBottom() {
            const container = document.getElementById('messagesContainer');
            container.scrollTop = container.scrollHeight;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));

            if (days === 0) {
                return 'Hari ini';
            } else if (days === 1) {
                return 'Kemarin';
            } else if (days < 7) {
                return `${days} hari lalu`;
            } else {
                return date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }
        }

        function formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML.replace(/\n/g, '<br>');
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            stopPolling();
        });
    </script>
@endsection
