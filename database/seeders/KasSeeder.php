<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kas;
use App\Models\User;
use App\Models\KodeAkun;
use Carbon\Carbon;

class KasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all admin users
        $adminUsers = User::where('role', 'admin')->get();

        if ($adminUsers->isEmpty()) {
            $this->command->warn('No admin users found. Please run UserSeeder first.');
            return;
        }

        // Sample account codes categories
        $pemasukanCategories = [
            'Infaq Jumat',
            'Donasi',
            'Zakat',
            'Sumbangan',
            'Infaq Ramadan',
        ];

        $pengeluaranCategories = [
            'Listrik',
            'Air',
            'Kebersihan',
            'Pemeliharaan',
            'Kegiatan',
            'Gaji Imam',
            'Gaji Muadzin',
            'ATK',
        ];

        foreach ($adminUsers as $admin) {
            // Get or create kode akun for this user
            $kodeAkunPemasukan = [];
            $kodeAkunPengeluaran = [];

            // Create kode akun for pemasukan
            foreach ($pemasukanCategories as $index => $category) {
                $kodeAkun = KodeAkun::firstOrCreate(
                    [
                        'user_id' => $admin->id,
                        'kode_akun' => '4-10' . ($index + 1),
                    ],
                    [
                        'nama_akun' => $category,
                        'kategori_akun' => 'Pemasukan',
                    ]
                );
                $kodeAkunPemasukan[] = $kodeAkun;
            }

            // Create kode akun for pengeluaran
            foreach ($pengeluaranCategories as $index => $category) {
                $kodeAkun = KodeAkun::firstOrCreate(
                    [
                        'user_id' => $admin->id,
                        'kode_akun' => '5-10' . ($index + 1),
                    ],
                    [
                        'nama_akun' => $category,
                        'kategori_akun' => 'Pengeluaran',
                    ]
                );
                $kodeAkunPengeluaran[] = $kodeAkun;
            }

            // Generate transactions for the last 3 months
            $startDate = Carbon::now()->subMonths(3);
            $endDate = Carbon::now();

            // Generate 30-50 random transactions per admin
            $transactionCount = rand(30, 50);

            for ($i = 0; $i < $transactionCount; $i++) {
                // Random date within the range
                $randomDate = Carbon::createFromTimestamp(
                    rand($startDate->timestamp, $endDate->timestamp)
                );

                // Randomly choose between pemasukan and pengeluaran (60% pemasukan, 40% pengeluaran)
                $jenis = rand(1, 100) <= 60 ? 'pemasukan' : 'pengeluaran';

                if ($jenis === 'pemasukan') {
                    $kodeAkun = $kodeAkunPemasukan[array_rand($kodeAkunPemasukan)];
                    // Random amount between 50,000 and 5,000,000
                    $nominal = rand(50, 5000) * 1000;
                } else {
                    $kodeAkun = $kodeAkunPengeluaran[array_rand($kodeAkunPengeluaran)];
                    // Random amount between 20,000 and 2,000,000
                    $nominal = rand(20, 2000) * 1000;
                }

                Kas::create([
                    'user_id' => $admin->id,
                    'kodeakun_id' => $kodeAkun->id,
                    'tanggal' => $randomDate->format('Y-m-d'),
                    'jenis' => $jenis,
                    'nominal' => $nominal,
                    'keterangan' => $this->generateKeterangan($jenis, $kodeAkun->nama_akun),
                ]);
            }

            $this->command->info("Generated {$transactionCount} transactions for admin: {$admin->name}");
        }

        $this->command->info('Kas seeder completed successfully!');
    }

    /**
     * Generate random keterangan based on transaction type
     */
    private function generateKeterangan($jenis, $namaAkun): string
    {
        $keteranganPemasukan = [
            "Infaq dari jamaah",
            "Donasi {$namaAkun}",
            "Penerimaan {$namaAkun}",
            "Kolekte shalat Jumat",
            "Sumbangan dari dermawan",
            "Infaq kotak amal",
            "Zakat fitrah",
            "Zakat mal",
        ];

        $keteranganPengeluaran = [
            "Pembayaran {$namaAkun}",
            "Biaya {$namaAkun} bulan ini",
            "Pengeluaran untuk {$namaAkun}",
            "Pembelian {$namaAkun}",
            "Maintenance {$namaAkun}",
            "Pembayaran rutin {$namaAkun}",
        ];

        if ($jenis === 'pemasukan') {
            return $keteranganPemasukan[array_rand($keteranganPemasukan)];
        } else {
            return $keteranganPengeluaran[array_rand($keteranganPengeluaran)];
        }
    }
}
