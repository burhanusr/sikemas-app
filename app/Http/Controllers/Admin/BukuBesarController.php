<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JurnalUmum;
use App\Models\KodeAkun;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BukuBesarController extends Controller
{
    public function index(Request $request)
    {
        // Determine which user's data to show
        $userId = $this->getUserId($request);
        $user = User::findOrFail($userId);

        $kodeAkun = KodeAkun::where('user_id', $userId)
            ->orderBy('kode_akun')
            ->get();

        $selectedAkun = null;
        $bukuBesar = collect();
        $saldoAwal = 0;
        $totalDebit = 0;
        $totalKredit = 0;
        $saldoAkhir = 0;

        if ($request->filled('kode_akun')) {
            $selectedAkun = KodeAkun::where('id', $request->kode_akun)
                ->where('user_id', $userId)
                ->first();

            if ($selectedAkun) {
                $query = JurnalUmum::with(['kas'])
                    ->where('user_id', $userId)
                    ->where('kodeakun_id', $selectedAkun->id);

                // Date from filter
                if ($request->filled('tanggal_dari')) {
                    // Hitung saldo awal
                    $saldoAwal = $this->hitungSaldoAwal($userId, $selectedAkun->id, $request->tanggal_dari);
                    $query->whereDate('tanggal', '>=', $request->tanggal_dari);
                }

                // Date to filter
                if ($request->filled('tanggal_sampai')) {
                    $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
                }

                $bukuBesar = $query->orderBy('tanggal')->orderBy('id')->get();

                // Hitung total dan saldo
                $totalDebit = $bukuBesar->sum('debit');
                $totalKredit = $bukuBesar->sum('kredit');

                // Saldo akhir = saldo awal + total debit - total kredit
                $saldoAkhir = $saldoAwal + $totalDebit - $totalKredit;

                // Tambahkan running balance untuk setiap transaksi
                $runningBalance = $saldoAwal;
                $bukuBesar->transform(function ($item) use (&$runningBalance) {
                    $runningBalance += $item->debit - $item->kredit;
                    $item->saldo = $runningBalance;
                    return $item;
                });
            }
        }

        return view('admin.buku-besar', compact(
            'kodeAkun',
            'selectedAkun',
            'bukuBesar',
            'saldoAwal',
            'totalDebit',
            'totalKredit',
            'saldoAkhir',
            'user'
        ));
    }

    private function hitungSaldoAwal($userId, $kodeAkunId, $tanggalDari)
    {
        $debit = JurnalUmum::where('user_id', $userId)
            ->where('kodeakun_id', $kodeAkunId)
            ->whereDate('tanggal', '<', $tanggalDari)
            ->sum('debit');

        $kredit = JurnalUmum::where('user_id', $userId)
            ->where('kodeakun_id', $kodeAkunId)
            ->whereDate('tanggal', '<', $tanggalDari)
            ->sum('kredit');

        return $debit - $kredit;
    }

    /**
     * Helper method to determine which user's data to show
     * Superadmin can view any user's data via ?user_id=X
     * Regular users can only view their own data
     */
    private function getUserId(Request $request)
    {
        if (Auth::user()->role === 'superadmin' && $request->filled('user_id')) {
            return $request->user_id;
        }

        return Auth::id();
    }
}
