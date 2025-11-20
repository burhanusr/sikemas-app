<?php

use App\Http\Controllers\Admin\BukuBesarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JurnalUmumController;
use App\Http\Controllers\Admin\KasController;
use App\Http\Controllers\Admin\KodeAkunController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\SuperAdmin\LaporanKeuanganController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\UserController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin routes
    Route::middleware('role:admin,superadmin')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Kode akun
        Route::resource('/admin/kode-akun', KodeAkunController::class);

        // Log Aktivitas
        Route::get('/admin/kas', [KasController::class, 'index'])->name('kas.index');

        // Pemasukan Pengeluaran
        Route::get('/admin/kas/{jenis}', [KasController::class, 'getKasByType'])->name('kas.kasType');
        Route::post('/admin/kas/{jenis}', [KasController::class, 'store'])->name('kas.store');
        Route::put('/admin/kas/{id}', [KasController::class, 'update'])->name('kas.update');
        Route::delete('/admin/kas/{id}', [KasController::class, 'destroy'])->name('kas.destroy');

        // Jurnal Umum
        Route::get('/admin/jurnal-umum', [JurnalUmumController::class, 'index'])->name('jurnal-umum.index');

        // Buku Besar
        Route::get('/admin/buku-besar', [BukuBesarController::class, 'index'])->name('buku-besar.index');

        Route::get('/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
        Route::post('/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
        Route::get('/discussions/{id}', [DiscussionController::class, 'show'])->name('discussions.show');
        Route::post('/discussions/{id}/messages', [DiscussionController::class, 'sendMessage'])->name('discussions.sendMessage');
        Route::get('/discussions/{id}/new-messages', [DiscussionController::class, 'getNewMessages'])->name('discussions.newMessages');
        Route::patch('/discussions/{id}/close', [DiscussionController::class, 'close'])->name('discussions.close');
        Route::patch('/discussions/{id}/reopen', [DiscussionController::class, 'reopen'])->name('discussions.reopen');
    });

    // SuperAdmin routes
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/superadmin/dashboard', [SuperAdminDashboardController::class, 'index'])->name('superadmin.dashboard');

        // User management routes
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/superadmin/laporan-keuangan-masjid', [LaporanKeuanganController::class, 'index'])->name('superadmin.laporan-keuangan');
    });
});

Route::get('/', function () {
    return redirect()->route('login');
});
