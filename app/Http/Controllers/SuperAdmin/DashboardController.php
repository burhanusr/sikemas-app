<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request or use defaults
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Count total users
        $totalUsers = User::count();

        // Count superadmins
        $totalSuperadmin = User::where('role', 'superadmin')->count();

        // Count regular admins
        $totalAdmin = User::where('role', 'admin')->count();

        // Get all admin user IDs (not superadmin)
        $adminUserIds = User::where('role', 'admin')->pluck('id');

        // Get total pemasukan from all admin masjid for current month
        $totalPemasukanAll = Kas::whereIn('user_id', $adminUserIds)
            ->where('jenis', 'pemasukan')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        // Get total pengeluaran from all admin masjid for current month
        $totalPengeluaranAll = Kas::whereIn('user_id', $adminUserIds)
            ->where('jenis', 'pengeluaran')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        // Calculate total saldo from all masjid
        // Get the latest saldo for each admin
        $totalSaldoAll = DB::table('kas as k1')
            ->select('k1.user_id', 'k1.saldo')
            ->whereIn('k1.user_id', $adminUserIds)
            ->whereRaw('k1.id = (
                SELECT k2.id
                FROM kas k2
                WHERE k2.user_id = k1.user_id
                ORDER BY k2.tanggal DESC, k2.id DESC
                LIMIT 1
            )')
            ->get()
            ->sum('saldo');

        // Get chart data for all admin masjid
        $chartData = $this->getChartData($adminUserIds, $startDate, $endDate);

        // Get recent admin activities (last 5 registered admins)
        $recentAdminActivities = User::where('role', 'admin')
            ->latest()
            ->limit(5)
            ->get();

        return view('superadmin.dashboard', compact(
            'totalUsers',
            'totalSuperadmin',
            'totalAdmin',
            'totalPemasukanAll',
            'totalPengeluaranAll',
            'totalSaldoAll',
            'chartData',
            'recentAdminActivities',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Generate chart data for all admin masjid
     */
    private function getChartData($adminUserIds, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Determine grouping based on date range
        $daysDiff = $start->diffInDays($end);

        if ($daysDiff <= 31) {
            // Daily grouping for ranges up to 31 days
            return $this->getDailyChartData($adminUserIds, $start, $end);
        } elseif ($daysDiff <= 365) {
            // Monthly grouping for ranges up to 1 year
            return $this->getMonthlyChartData($adminUserIds, $start, $end);
        } else {
            // Yearly grouping for longer ranges
            return $this->getYearlyChartData($adminUserIds, $start, $end);
        }
    }

    /**
     * Get daily chart data for all admin masjid
     */
    private function getDailyChartData($adminUserIds, $start, $end)
    {
        $labels = [];
        $pemasukan = [];
        $pengeluaran = [];

        // Get all transactions in the date range from all admins
        $transactions = Kas::whereIn('user_id', $adminUserIds)
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
     * Get monthly chart data for all admin masjid
     */
    private function getMonthlyChartData($adminUserIds, $start, $end)
    {
        $labels = [];
        $pemasukan = [];
        $pengeluaran = [];

        // Get all transactions in the date range from all admins
        $transactions = Kas::whereIn('user_id', $adminUserIds)
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
     * Get yearly chart data for all admin masjid
     */
    private function getYearlyChartData($adminUserIds, $start, $end)
    {
        $labels = [];
        $pemasukan = [];
        $pengeluaran = [];

        // Get all transactions in the date range from all admins
        $transactions = Kas::whereIn('user_id', $adminUserIds)
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
