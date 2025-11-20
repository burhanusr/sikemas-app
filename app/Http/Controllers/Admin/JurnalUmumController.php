<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JurnalUmum;
use App\Models\KodeAkun;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JurnalUmumController extends Controller
{
    public function index(Request $request)
    {
        // Determine which user's data to show
        $userId = $this->getUserId($request);
        $user = User::findOrFail($userId);

        $query = JurnalUmum::with(['kodeAkun', 'kas'])
            ->where('user_id', $userId);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('kodeAkun', function ($q2) use ($search) {
                    $q2->where('nama_akun', 'like', "%{$search}%")
                        ->orWhere('kode_akun', 'like', "%{$search}%");
                })
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Kode Akun filter
        if ($request->filled('kode_akun')) {
            $query->where('kodeakun_id', $request->kode_akun);
        }

        // Date from filter
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        // Date to filter
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        $jurnal = $query->latest('tanggal')->latest('id')->paginate(20)->withQueryString();
        $kodeAkun = KodeAkun::where('user_id', $userId)
            ->orderBy('kode_akun')
            ->get();

        // Hitung total debit dan kredit (with filters applied)
        $totalDebit = $query->sum('debit');
        $totalKredit = $query->sum('kredit');

        return view('admin.jurnal-umum', compact('jurnal', 'kodeAkun', 'totalDebit', 'totalKredit', 'user'));
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
