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
        // Get year from request or use current year
        $year = $request->get('year', now()->year);
        $transactionType = $request->get('transaction_type', 'pemasukan'); // Default to pemasukan

        // Count total users
        $totalUsers = User::count();

        // Count superadmins
        $totalSuperadmin = User::where('role', 'superadmin')->count();

        // Count regular admins
        $totalAdmin = User::where('role', 'admin')->count();

        // Get all admin users
        $adminUsers = User::where('role', 'admin')->get();

        // Get total pemasukan from all admin masjid for current month
        $totalPemasukanAll = Kas::whereHas('user', function ($query) {
            $query->where('role', 'admin');
        })
            ->where('jenis', 'pemasukan')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        // Get total pengeluaran from all admin masjid for current month
        $totalPengeluaranAll = Kas::whereHas('user', function ($query) {
            $query->where('role', 'admin');
        })
            ->where('jenis', 'pengeluaran')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        // Calculate total saldo
        $totalSaldoAll = $totalPemasukanAll - $totalPengeluaranAll;

        // Get chart data for the selected year
        $chartData = $this->getYearlyChartData($adminUsers, $year, $transactionType);

        // Get admin data summary for the selected year
        $adminData = $this->getAdminYearSummary($adminUsers, $year, $transactionType);

        // Get recent admin activities (last 5 registered admins)
        $recentAdminActivities = User::where('role', 'admin')
            ->latest()
            ->limit(5)
            ->get();

        // Get available years for dropdown
        $availableYears = Kas::selectRaw('DISTINCT YEAR(tanggal) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([now()->year]);
        }

        return view('superadmin.dashboard', compact(
            'totalUsers',
            'totalSuperadmin',
            'totalAdmin',
            'totalPemasukanAll',
            'totalPengeluaranAll',
            'totalSaldoAll',
            'chartData',
            'recentAdminActivities',
            'year',
            'transactionType',
            'adminData',
            'availableYears'
        ));
    }

    /**
     * Generate chart data for all admins by month in a year
     */
    private function getYearlyChartData($adminUsers, $year, $transactionType)
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Oct', 'Nov', 'Des'];
        $datasets = [];

        // Color palette for different admins
        $colors = [
            ['border' => 'rgb(59, 130, 246)', 'bg' => 'rgba(59, 130, 246, 0.1)'],    // Blue
            ['border' => 'rgb(249, 115, 22)', 'bg' => 'rgba(249, 115, 22, 0.1)'],    // Orange
            ['border' => 'rgb(34, 197, 94)', 'bg' => 'rgba(34, 197, 94, 0.1)'],      // Green
            ['border' => 'rgb(168, 85, 247)', 'bg' => 'rgba(168, 85, 247, 0.1)'],    // Purple
            ['border' => 'rgb(236, 72, 153)', 'bg' => 'rgba(236, 72, 153, 0.1)'],    // Pink
            ['border' => 'rgb(14, 165, 233)', 'bg' => 'rgba(14, 165, 233, 0.1)'],    // Sky
            ['border' => 'rgb(234, 179, 8)', 'bg' => 'rgba(234, 179, 8, 0.1)'],      // Yellow
            ['border' => 'rgb(239, 68, 68)', 'bg' => 'rgba(239, 68, 68, 0.1)'],      // Red
        ];

        foreach ($adminUsers as $index => $admin) {
            $monthlyData = [];

            // Get data for each month (1-12)
            for ($month = 1; $month <= 12; $month++) {
                $total = Kas::where('user_id', $admin->id)
                    ->where('jenis', $transactionType)
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month)
                    ->sum('nominal');

                $monthlyData[] = $total;
            }

            // Use color from palette (cycle if more admins than colors)
            $colorIndex = $index % count($colors);
            $color = $colors[$colorIndex];

            $datasets[] = [
                'label' => $admin->name . ' - ' . ($admin->organization ?? 'Masjid'),
                'data' => $monthlyData,
                'backgroundColor' => $color['bg'],
                'borderColor' => $color['border'],
                'borderWidth' => 3,
                'fill' => true,
                'tension' => 0.4,
                'pointBackgroundColor' => $color['border'],
                'pointBorderColor' => '#fff',
                'pointBorderWidth' => 2,
                'pointRadius' => 5,
                'pointHoverRadius' => 7
            ];
        }

        return [
            'labels' => $months,
            'datasets' => $datasets,
            'type' => $transactionType
        ];
    }

    /**
     * Get admin summary data for the year
     */
    private function getAdminYearSummary($adminUsers, $year, $transactionType)
    {
        $adminData = $adminUsers->map(function ($admin) use ($year, $transactionType) {
            $total = Kas::where('user_id', $admin->id)
                ->where('jenis', $transactionType)
                ->whereYear('tanggal', $year)
                ->sum('nominal');

            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'organization' => $admin->organization ?? 'Tidak ada organisasi',
                'total' => $total
            ];
        })->sortByDesc('total');

        return $adminData;
    }
}
