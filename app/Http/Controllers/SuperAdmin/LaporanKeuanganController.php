<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'admin')
            ->select('users.*');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('users.organization', 'like', "%{$search}%");
            });
        }

        $mosques = $query->orderBy('users.organization')
            ->paginate(15)
            ->withQueryString();

        // Calculate saldo for each mosque
        foreach ($mosques as $mosque) {
            // Get total pemasukan
            $totalPemasukan = Kas::where('user_id', $mosque->id)
                ->where('jenis', 'pemasukan')
                ->sum('nominal');

            // Get total pengeluaran
            $totalPengeluaran = Kas::where('user_id', $mosque->id)
                ->where('jenis', 'pengeluaran')
                ->sum('nominal');

            // Calculate saldo
            $mosque->saldo = $totalPemasukan - $totalPengeluaran;
        }

        return view('superadmin.laporan-keuangan', compact('mosques'));
    }
}
