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
            ->select('users.*')
            ->leftJoin('kas', function ($join) {
                $join->on('users.id', '=', 'kas.user_id')
                    ->whereRaw('kas.id = (SELECT MAX(id) FROM kas WHERE kas.user_id = users.id)');
            })
            ->addSelect('kas.saldo');

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

        return view('superadmin.laporan-keuangan', compact('mosques'));
    }
}
