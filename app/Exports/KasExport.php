<?php

namespace App\Exports;

use App\Models\Kas;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KasExport implements FromView, WithStyles, WithColumnWidths, WithEvents
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

    public function view(): View
    {
        // Calculate previous month's balance manually
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

        return view('exports.kas', [
            'previousBalance' => $previousBalance,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'month' => $this->month,
            'year' => $this->year,
            'masjid' => $masjid
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // Tanggal
            'C' => 25,  // Kode Akun
            'D' => 35,  // Keterangan
            'E' => 15,  // Nominal
            'F' => 15,  // Jumlah
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge title cells
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');

                // Get the last row
                $lastRow = $sheet->getHighestRow();

                // Style all borders
                $sheet->getStyle('A4:F' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Style headers (row 4)
                $sheet->getStyle('A4:F4')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E0E0E0'],
                    ],
                ]);

                // Center align certain columns
                $sheet->getStyle('A4:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B4:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Format numbers with thousand separator
                $sheet->getStyle('E5:F' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');

                // Bold section headers and totals
                foreach ($sheet->getRowIterator() as $row) {
                    $cellValue = $sheet->getCell('A' . $row->getRowIndex())->getValue();
                    if (
                        in_array($cellValue, ['Saldo Masjid', 'Pemasukan', 'Pengeluaran', 'Saldo Akhir']) ||
                        strpos($cellValue, 'Total') === 0
                    ) {
                        $sheet->getStyle('A' . $row->getRowIndex() . ':F' . $row->getRowIndex())->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F0F0F0'],
                            ],
                        ]);
                    }
                }
            },
        ];
    }
}
