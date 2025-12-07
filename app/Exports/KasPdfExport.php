<?php

namespace App\Exports;

use App\Models\Kas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class KasPdfExport
{
    protected $month;
    protected $year;
    protected $userId;

    public function __construct($month = null, $year = null, $userId = null)
    {
        $this->month = $month ?? now()->month;
        $this->year = $year ?? now()->year;
        $this->userId = $userId ?? Auth::id();
    }

    public function getData()
    {
        // Calculate previous month's balance
        $previousTransactions = Kas::where('user_id', $this->userId)
            ->where(function ($query) {
                $query->whereYear('tanggal', '<', $this->year)
                    ->orWhere(function ($q) {
                        $q->whereYear('tanggal', $this->year)
                            ->whereMonth('tanggal', '<', $this->month);
                    });
            })
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $previousBalance = 0;
        foreach ($previousTransactions as $transaction) {
            if ($transaction->jenis === 'pemasukan') {
                $previousBalance += $transaction->nominal;
            } else {
                $previousBalance -= $transaction->nominal;
            }
        }

        // Get current month's data grouped by jenis
        $pemasukan = Kas::with(['kodeakun'])
            ->where('user_id', $this->userId)
            ->whereYear('tanggal', $this->year)
            ->whereMonth('tanggal', $this->month)
            ->where('jenis', 'pemasukan')
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $pengeluaran = Kas::with(['kodeakun'])
            ->where('user_id', $this->userId)
            ->whereYear('tanggal', $this->year)
            ->whereMonth('tanggal', $this->month)
            ->where('jenis', 'pengeluaran')
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $masjid = User::where('id', $this->userId)->first();

        return [
            'previousBalance' => $previousBalance,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'month' => $this->month,
            'year' => $this->year,
            'masjid' => $masjid
        ];
    }
}
