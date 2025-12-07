<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get year from request or use current year
        $selectedYear = $request->get('year', now()->year);

        // Get total income (all time for dashboard stats)
        $totalPemasukan = Kas::where('user_id', $userId)
            ->where('jenis', 'pemasukan')
            ->sum(DB::raw('CAST(nominal AS DECIMAL(15,2))'));

        // Get count of income transactions
        $jumlahTransaksiPemasukan = Kas::where('user_id', $userId)
            ->where('jenis', 'pemasukan')
            ->count();

        // Get total expenses (all time for dashboard stats)
        $totalPengeluaran = Kas::where('user_id', $userId)
            ->where('jenis', 'pengeluaran')
            ->sum(DB::raw('CAST(nominal AS DECIMAL(15,2))'));

        // Get count of expense transactions
        $jumlahTransaksiPengeluaran = Kas::where('user_id', $userId)
            ->where('jenis', 'pengeluaran')
            ->count();

        // Calculate balance
        $saldoMasjid = $totalPemasukan - $totalPengeluaran;

        // Get chart data for the selected year (all 12 months)
        $chartData = $this->getYearlyChartData($userId, $selectedYear);

        // Get recent transactions (last 5)
        $recentTransactions = Kas::where('user_id', $userId)
            ->with('kodeAkun')
            ->latest('tanggal')
            ->latest('id')
            ->limit(5)
            ->get();

        // Get available years for dropdown
        $availableYears = $this->getAvailableYears($userId);

        return view('admin.dashboard', compact(
            'saldoMasjid',
            'totalPemasukan',
            'jumlahTransaksiPemasukan',
            'totalPengeluaran',
            'jumlahTransaksiPengeluaran',
            'chartData',
            'recentTransactions',
            'selectedYear',
            'availableYears'
        ));
    }

    /**
     * Get chart data for all 12 months in a specific year
     */
    private function getYearlyChartData($userId, $year)
    {
        $labels = [];
        $pemasukan = [];
        $pengeluaran = [];

        // Get all transactions for the selected year
        $transactions = Kas::where('user_id', $userId)
            ->whereYear('tanggal', $year)
            ->select(
                DB::raw('MONTH(tanggal) as month'),
                'jenis',
                DB::raw('SUM(CAST(nominal AS DECIMAL(15,2))) as total')
            )
            ->groupBy('month', 'jenis')
            ->orderBy('month')
            ->get()
            ->groupBy('month');

        // Indonesian month names
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // Generate data for all 12 months
        for ($month = 1; $month <= 12; $month++) {
            $labels[] = $monthNames[$month];

            $monthTransactions = $transactions->get($month, collect());

            $pemasukan[] = (float) $monthTransactions->where('jenis', 'pemasukan')->sum('total');
            $pengeluaran[] = (float) $monthTransactions->where('jenis', 'pengeluaran')->sum('total');
        }

        return [
            'labels' => $labels,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran
        ];
    }

    /**
     * Get list of years that have transaction data
     */
    private function getAvailableYears($userId)
    {
        $years = Kas::where('user_id', $userId)
            ->select(DB::raw('DISTINCT YEAR(tanggal) as year'))
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // If no data exists, include current year
        if (empty($years)) {
            $years = [now()->year];
        }

        return $years;
    }
}
