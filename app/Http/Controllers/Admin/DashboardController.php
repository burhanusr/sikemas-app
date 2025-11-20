<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\JurnalUmum;
use App\Models\KodeAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get date range from request or use defaults
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Calculate current balance (saldo masjid)
        $saldoMasjid = Kas::where('user_id', $userId)
            ->latest('tanggal')
            ->latest('id')
            ->value('saldo') ?? 0;

        // Get total income for current month
        $totalPemasukan = Kas::where('user_id', $userId)
            ->where('jenis', 'pemasukan')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        // Get count of income transactions
        $jumlahTransaksiPemasukan = Kas::where('user_id', $userId)
            ->where('jenis', 'pemasukan')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Get total expenses for current month
        $totalPengeluaran = Kas::where('user_id', $userId)
            ->where('jenis', 'pengeluaran')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        // Get count of expense transactions
        $jumlahTransaksiPengeluaran = Kas::where('user_id', $userId)
            ->where('jenis', 'pengeluaran')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Get chart data based on date range
        $chartData = $this->getChartData($userId, $startDate, $endDate);

        // Get recent transactions (last 5)
        $recentTransactions = Kas::where('user_id', $userId)
            ->with('kodeAkun')
            ->latest('tanggal')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'saldoMasjid',
            'totalPemasukan',
            'jumlahTransaksiPemasukan',
            'totalPengeluaran',
            'jumlahTransaksiPengeluaran',
            'chartData',
            'recentTransactions',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Generate chart data for the specified date range
     */
    private function getChartData($userId, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Determine grouping based on date range
        $daysDiff = $start->diffInDays($end);

        if ($daysDiff <= 31) {
            // Daily grouping for ranges up to 31 days
            return $this->getDailyChartData($userId, $start, $end);
        } elseif ($daysDiff <= 365) {
            // Monthly grouping for ranges up to 1 year
            return $this->getMonthlyChartData($userId, $start, $end);
        } else {
            // Yearly grouping for longer ranges
            return $this->getYearlyChartData($userId, $start, $end);
        }
    }

    /**
     * Get daily chart data
     */
    private function getDailyChartData($userId, $start, $end)
    {
        $labels = [];
        $pemasukan = [];
        $pengeluaran = [];

        // Get all transactions in the date range
        $transactions = Kas::where('user_id', $userId)
            ->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->select(
                DB::raw('DATE(tanggal) as date'),
                'jenis',
                DB::raw('SUM(nominal) as total')
            )
            ->groupBy('date', 'jenis')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        // Generate labels and data for each day
        $currentDate = $start->copy();
        while ($currentDate->lte($end)) {
            $dateKey = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('d M');

            $dayTransactions = $transactions->get($dateKey, collect());

            $pemasukan[] = $dayTransactions->where('jenis', 'pemasukan')->sum('total');
            $pengeluaran[] = $dayTransactions->where('jenis', 'pengeluaran')->sum('total');

            $currentDate->addDay();
        }

        return [
            'labels' => $labels,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran
        ];
    }

    /**
     * Get monthly chart data
     */
    private function getMonthlyChartData($userId, $start, $end)
    {
        $labels = [];
        $pemasukan = [];
        $pengeluaran = [];

        // Get all transactions in the date range
        $transactions = Kas::where('user_id', $userId)
            ->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->select(
                DB::raw('YEAR(tanggal) as year'),
                DB::raw('MONTH(tanggal) as month'),
                'jenis',
                DB::raw('SUM(nominal) as total')
            )
            ->groupBy('year', 'month', 'jenis')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->groupBy(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });

        // Generate labels and data for each month
        $currentDate = $start->copy()->startOfMonth();
        $endOfMonth = $end->copy()->endOfMonth();

        while ($currentDate->lte($endOfMonth)) {
            $monthKey = $currentDate->format('Y-m');
            $labels[] = $currentDate->format('M Y');

            $monthTransactions = $transactions->get($monthKey, collect());

            $pemasukan[] = $monthTransactions->where('jenis', 'pemasukan')->sum('total');
            $pengeluaran[] = $monthTransactions->where('jenis', 'pengeluaran')->sum('total');

            $currentDate->addMonth();
        }

        return [
            'labels' => $labels,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran
        ];
    }

    /**
     * Get yearly chart data
     */
    private function getYearlyChartData($userId, $start, $end)
    {
        $labels = [];
        $pemasukan = [];
        $pengeluaran = [];

        // Get all transactions in the date range
        $transactions = Kas::where('user_id', $userId)
            ->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->select(
                DB::raw('YEAR(tanggal) as year'),
                'jenis',
                DB::raw('SUM(nominal) as total')
            )
            ->groupBy('year', 'jenis')
            ->orderBy('year')
            ->get()
            ->groupBy('year');

        // Generate labels and data for each year
        $currentYear = $start->year;
        $endYear = $end->year;

        while ($currentYear <= $endYear) {
            $labels[] = (string) $currentYear;

            $yearTransactions = $transactions->get($currentYear, collect());

            $pemasukan[] = $yearTransactions->where('jenis', 'pemasukan')->sum('total');
            $pengeluaran[] = $yearTransactions->where('jenis', 'pengeluaran')->sum('total');

            $currentYear++;
        }

        return [
            'labels' => $labels,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran
        ];
    }
}
