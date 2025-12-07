<?php

namespace App\Http\Controllers\Admin;

use App\Exports\KasExcelExport;
use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\KodeAkun;
use App\Models\JurnalUmum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class KasController extends Controller
{
    public function index(Request $request)
    {
        // Determine which user's data to show
        $userId = $this->getUserId($request);
        $user = User::findOrFail($userId);

        $query = Kas::where('user_id', $userId);

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

        // Jenis filter (pemasukan/pengeluaran)
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Date from filter
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        // Date to filter
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        $kas = $query->latest('tanggal')->latest('id')->paginate(10)->withQueryString();

        $totalPemasukan = Kas::where('user_id', $userId)->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = Kas::where('user_id', $userId)->where('jenis', 'pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('admin.log-keuangan', compact('kas', 'user', 'saldo', 'totalPemasukan', 'totalPengeluaran'));
    }

    public function getKasByType(Request $request, $jenis)
    {
        // Determine which user's data to show
        $userId = $this->getUserId($request);
        $user = User::findOrFail($userId);

        $query = Kas::with('kodeAkun')
            ->where('jenis', $jenis)
            ->where('user_id', $userId);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('kodeAkun', function ($q) use ($search) {
                    $q->where('nama_akun', 'like', "%{$search}%")
                        ->orWhere('kode_akun', 'like', "%{$search}%");
                })->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Kode Akun filter
        if ($request->filled('kode_akun')) {
            $query->where('kodeakun_id', $request->kode_akun);
        }

        // Date filter
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $kas = $query->latest('tanggal')->paginate(5)->withQueryString();
        $kodeAkun = KodeAkun::where('user_id', $userId)->orderBy('kode_akun')->get();

        return view('admin.kas', compact('kas', 'kodeAkun', 'jenis', 'user'));
    }

    public function store(Request $request, $jenis)
    {
        // Only allow the owner to create
        if ($request->has('user_id') && Auth::user()->role !== 'superadmin') {
            abort(403, 'Unauthorized action.');
        }

        $userId = Auth::id();

        $request->validate([
            'tanggal' => 'required|date',
            'kodeakun_id' => 'required|exists:kode_akun,id',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($request->nominal <= 0) {
            return redirect()->back()
                ->with('error', 'Nominal harus lebih besar dari 0.')
                ->withInput();
        }

        DB::transaction(function () use ($request, $jenis, $userId) {
            $kas = Kas::create([
                'user_id' => $userId,
                'tanggal' => $request->tanggal,
                'kodeakun_id' => $request->kodeakun_id,
                'jenis' => $jenis,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
            ]);

            // Buat jurnal umum otomatis
            $this->createJurnalEntry($kas);
        });

        return redirect()->route('kas.kasType', $jenis)
            ->with('success', "Data {$jenis} berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $kas = Kas::findOrFail($id);

        // Only allow the owner to update
        if ($kas->user_id !== Auth::id() && Auth::user()->role !== 'superadmin') {
            abort(403, 'Unauthorized action.');
        }

        $jenis = $kas->jenis;

        $request->validate([
            'tanggal' => 'required|date',
            'kodeakun_id' => 'required|exists:kode_akun,id',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($request->nominal <= 0) {
            return redirect()->back()
                ->with('error', 'Nominal harus lebih besar dari 0.');
        }

        DB::transaction(function () use ($kas, $request, $jenis) {
            $kas->update([
                'tanggal' => $request->tanggal,
                'kodeakun_id' => $request->kodeakun_id,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
            ]);

            // Update jurnal umum
            $this->updateJurnalEntry($kas);
        });

        $redirectRoute = $request->has('user_id')
            ? route('kas.index', ['user_id' => $kas->user_id, 'jenis' => $jenis])
            : route('kas.kasType', $jenis);

        return redirect($redirectRoute)
            ->with('success', "Data {$jenis} berhasil diperbarui.");
    }

    public function destroy(Request $request, $id)
    {
        $kas = Kas::findOrFail($id);

        // Only allow the owner to delete
        if ($kas->user_id !== Auth::id() && Auth::user()->role !== 'superadmin') {
            abort(403, 'Unauthorized action.');
        }

        $jenis = $kas->jenis;

        DB::transaction(function () use ($kas) {
            $kas->delete();
        });

        $redirectRoute = $request->has('user_id')
            ? route('kas.index', ['user_id' => $kas->user_id, 'jenis' => $jenis])
            : route('kas.kasType', $jenis);

        return redirect($redirectRoute)
            ->with('success', "Data {$jenis} berhasil dihapus.");
    }

    private function createJurnalEntry($kas)
    {
        if ($kas->jenis === 'pemasukan') {
            JurnalUmum::create([
                'user_id' => $kas->user_id,
                'kas_id' => $kas->id,
                'tanggal' => $kas->tanggal,
                'kodeakun_id' => $kas->kodeakun_id,
                'keterangan' => $kas->keterangan,
                'debit' => $kas->nominal,
                'kredit' => 0,
            ]);

            JurnalUmum::create([
                'user_id' => $kas->user_id,
                'kas_id' => $kas->id,
                'tanggal' => $kas->tanggal,
                'kodeakun_id' => $kas->kodeakun_id,
                'keterangan' => $kas->keterangan,
                'debit' => 0,
                'kredit' => $kas->nominal,
            ]);
        } else {
            // Debit: Akun Pengeluaran, Kredit: Kas
            JurnalUmum::create([
                'user_id' => $kas->user_id,
                'kas_id' => $kas->id,
                'tanggal' => $kas->tanggal,
                'kodeakun_id' => $kas->kodeakun_id,
                'keterangan' => $kas->keterangan,
                'debit' => $kas->nominal,
                'kredit' => 0,
            ]);

            JurnalUmum::create([
                'user_id' => $kas->user_id,
                'kas_id' => $kas->id,
                'tanggal' => $kas->tanggal,
                'kodeakun_id' => $kas->kodeakun_id,
                'keterangan' => $kas->keterangan,
                'debit' => 0,
                'kredit' => $kas->nominal,
            ]);
        }
    }

    private function updateJurnalEntry($kas)
    {
        JurnalUmum::where('kas_id', $kas->id)->delete();

        $this->createJurnalEntry($kas);
    }

    private function getUserId(Request $request)
    {
        if (Auth::user()->role === 'superadmin' && $request->filled('user_id')) {
            return $request->user_id;
        }

        return Auth::id();
    }

    public function export(Request $request)
    {
        $userId = $this->getUserId($request);
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $fileName = 'Laporan_Kas_' . \Carbon\Carbon::create($year, $month)->format('F_Y') . '.xlsx';

        return Excel::download(new KasExcelExport($month, $year, $userId), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $userId = $this->getUserId($request);
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $pdfExport = new \App\Exports\KasPdfExport($month, $year, $userId);
        $data = $pdfExport->getData();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.kas-pdf', $data);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Set options
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        $fileName = 'Laporan_Kas_' . \Carbon\Carbon::create($year, $month)->format('F_Y') . '.pdf';

        return $pdf->download($fileName);
    }
}
