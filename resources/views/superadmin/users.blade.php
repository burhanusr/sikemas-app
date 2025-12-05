@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

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
    <div class="rounded-xl border border-blue-100 bg-white shadow-lg">
        <div class="space-y-6 p-6">
            <!-- Header Section -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h2>
                    <p class="mt-1 text-gray-600">Kelola pengguna dan hak akses sistem</p>
                </div>
                <button onclick="openCreateModal()"
                    class="inline-flex transform cursor-pointer items-center rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 px-4 py-2.5 font-semibold text-white shadow-md transition-all hover:from-blue-600 hover:to-indigo-700 hover:shadow-lg">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Pengguna
                </button>
            </div>

            <!-- Success/Error Alert -->
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

            <!-- Table Card -->
            <div class="overflow-hidden rounded-xl border border-blue-100 bg-white shadow-sm">
                <!-- Search & Filter Section -->
                <div class="border-b border-blue-100 p-4">
                    <form method="GET" action="{{ route('users.index') }}" class="flex flex-col gap-4 sm:flex-row">
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama atau email..."
                                    class="w-full rounded-lg border border-blue-200 py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <select name="role"
                            class="rounded-lg border border-blue-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Super Admin
                            </option>
                        </select>
                        <button type="submit"
                            class="rounded-lg bg-blue-500 px-4 py-2 text-white transition-colors hover:bg-blue-600">
                            Filter
                        </button>
                        @if (request('search') || request('role'))
                            <a href="{{ route('users.index') }}"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 transition-colors hover:bg-gray-50">
                                Reset
                            </a>
                        @endif
                    </form>
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
                                    Nama</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Email</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Role</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Tanggal Dibuat</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-100">
                            @forelse($users as $index => $user)
                                <tr class="transition-colors hover:bg-blue-50/50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $users->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $user->email }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @php
                                            $roleColors = [
                                                'superadmin' => 'bg-purple-100 text-purple-700',
                                                'admin' => 'bg-blue-100 text-blue-700',
                                                'user' => 'bg-gray-100 text-gray-700',
                                            ];
                                            $roleLabels = [
                                                'superadmin' => 'Super Admin',
                                                'admin' => 'Admin',
                                                'user' => 'User',
                                            ];
                                            $color = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-700';
                                            $label = $roleLabels[$user->role] ?? ucfirst($user->role);
                                        @endphp
                                        <span
                                            class="{{ $color }} inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick='openEditModal(@json($user))'
                                                class="inline-flex items-center rounded-lg bg-blue-500 px-3 py-1.5 text-sm font-medium text-white transition-all hover:bg-blue-600 hover:shadow-md">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button
                                                onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')"
                                                class="inline-flex items-center rounded-lg bg-red-500 px-3 py-1.5 text-sm font-medium text-white transition-all hover:bg-red-600 hover:shadow-md"
                                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
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
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500">Belum ada data pengguna</p>
                                        <p class="mt-1 text-gray-400">Silakan tambahkan pengguna baru</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($users->hasPages())
                    <div class="mt-6 rounded-xl border border-gray-100 bg-white p-4 shadow-sm">
                        {{ $users->links('components.pagination') }}
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
            <div class="rounded-t-xl bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">Tambah Pengguna Baru</h3>
                        <p class="mt-1 text-blue-50">Masukkan informasi pengguna baru</p>
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
            <form action="{{ route('users.store') }}" method="POST" class="space-y-4 p-6">
                @csrf

                <!-- Two Column Grid -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Name -->
                    <div>
                        <label for="create_name" class="mb-2 block text-sm font-semibold text-gray-700">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="create_name" name="name" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="create_email" class="mb-2 block text-sm font-semibold text-gray-700">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="create_email" name="email" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="email@example.com">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="create_password" class="mb-2 block text-sm font-semibold text-gray-700">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="create_password" name="password" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Minimal 8 karakter">
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="create_password_confirmation" class="mb-2 block text-sm font-semibold text-gray-700">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="create_password_confirmation" name="password_confirmation" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Ulangi password">
                    </div>
                </div>

                <!-- Role (Full Width) -->
                <div>
                    <label for="create_role" class="mb-2 block text-sm font-semibold text-gray-700">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select id="create_role" name="role" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Super Admin</option>
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
                            <h4 class="mb-1 text-sm font-semibold text-blue-900">Panduan Role</h4>
                            <ul class="space-y-1 text-sm text-blue-800">
                                <li><strong>Admin:</strong> Akses penuh untuk mengelola data</li>
                                <li><strong>Super Admin:</strong> Akses penuh termasuk manajemen pengguna</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-3 font-semibold text-white shadow-md transition-all hover:from-blue-600 hover:to-indigo-700 hover:shadow-lg">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Pengguna
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
            <div class="rounded-t-xl bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">Edit Pengguna</h3>
                        <p class="mt-1 text-amber-50">Perbarui informasi pengguna</p>
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

                <!-- Two Column Grid -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Name -->
                    <div>
                        <label for="edit_name" class="mb-2 block text-sm font-semibold text-gray-700">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="edit_name" name="name" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-amber-500"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="edit_email" class="mb-2 block text-sm font-semibold text-gray-700">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="edit_email" name="email" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-amber-500"
                            placeholder="email@example.com">
                    </div>

                    <!-- Password (Optional) -->
                    <div>
                        <label for="edit_password" class="mb-2 block text-sm font-semibold text-gray-700">
                            Password Baru (Opsional)
                        </label>
                        <input type="password" id="edit_password" name="password"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-amber-500"
                            placeholder="Kosongkan jika tidak ingin mengubah">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="edit_password_confirmation" class="mb-2 block text-sm font-semibold text-gray-700">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" id="edit_password_confirmation" name="password_confirmation"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-amber-500"
                            placeholder="Ulangi password baru">
                    </div>
                </div>

                <!-- Role (Full Width) -->
                <div>
                    <label for="edit_role" class="mb-2 block text-sm font-semibold text-gray-700">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select id="edit_role" name="role" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="">Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Super Admin</option>
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
                            <p class="text-sm text-amber-800">Pastikan perubahan data pengguna tidak mempengaruhi akses
                                sistem yang sedang berjalan.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-3 font-semibold text-white shadow-md transition-all hover:from-amber-600 hover:to-orange-700 hover:shadow-lg">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Pengguna
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
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Hapus Pengguna?</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Apakah Anda yakin ingin menghapus pengguna
                        <span class="font-semibold" id="delete_name_display"></span>
                        (<span id="delete_email_display"></span>)?
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
        // Bulk Selection
        const selectAllCheckbox = document.getElementById('selectAll');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const selectedCount = document.getElementById('selectedCount');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');

        selectAllCheckbox.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.checked = this.checked;
                }
            });
            updateBulkDeleteButton();
        });

        userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkDeleteButton);
        });

        function updateBulkDeleteButton() {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            const count = checkedBoxes.length;

            selectedCount.textContent = `${count} dipilih`;
            bulkDeleteBtn.disabled = count === 0;

            // Update select all checkbox state
            const enabledCheckboxes = document.querySelectorAll('.user-checkbox:not([disabled])');
            const checkedEnabledBoxes = document.querySelectorAll('.user-checkbox:not([disabled]):checked');
            selectAllCheckbox.checked = enabledCheckboxes.length > 0 && enabledCheckboxes.length === checkedEnabledBoxes
                .length;
        }

        function confirmBulkDelete() {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            const count = checkedBoxes.length;

            if (count === 0) return;

            if (confirm(
                    `Apakah Anda yakin ingin menghapus ${count} pengguna terpilih? Tindakan ini tidak dapat dibatalkan!`)) {
                bulkDeleteForm.submit();
            }
        }

        // Create Modal Functions
        function openCreateModal() {
            const modal = document.getElementById('createModal');
            const content = modal.querySelector('.modal-content');

            modal.classList.remove('hidden');
            modal.classList.add('flex', 'show');
            content.classList.add('show');
            document.body.style.overflow = 'hidden';

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
                document.getElementById('create_name').value = '';
                document.getElementById('create_email').value = '';
                document.getElementById('create_password').value = '';
                document.getElementById('create_password_confirmation').value = '';
                document.getElementById('create_role').value = '';
            }, 300);
        }

        // Edit Modal Functions
        function openEditModal(user) {
            const modal = document.getElementById('editModal');
            const content = modal.querySelector('.modal-content');
            const form = document.getElementById('editForm');

            // Set form action with proper route
            form.action = "{{ url('users') }}/" + user.id;

            // Populate form fields
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_password_confirmation').value = '';
            document.getElementById('edit_role').value = user.role;

            // Show modal with animation
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'show');
            content.classList.add('show');
            document.body.style.overflow = 'hidden';

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
        function openDeleteModal(id, name, email) {
            const modal = document.getElementById('deleteModal');
            const content = modal.querySelector('.modal-content');
            const form = document.getElementById('deleteForm');

            // Set form action with proper route
            form.action = "{{ url('users') }}/" + id;

            // Set display text
            document.getElementById('delete_name_display').textContent = name;
            document.getElementById('delete_email_display').textContent = email;

            // Show modal with animation
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'show');
            content.classList.add('show');
            document.body.style.overflow = 'hidden';

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
