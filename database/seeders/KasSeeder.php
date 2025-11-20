<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa user dan kodeakun
        $userIds = DB::table('users')->pluck('id')->toArray();
        $kodeAkunIds = DB::table('kode_akun')->pluck('id')->toArray();

        if (empty($userIds) || empty($kodeAkunIds)) {
            $this->command->warn('Seeder Kas: users or kode_akun is empty, skipping.');
            return;
        }

        $jenis = ['pemasukan', 'pengeluaran'];

        $saldo = 0;

        for ($i = 1; $i <= 20; $i++) {
            // $randomUser = $userIds[array_rand($userIds)];
            $randomKodeAkun = $kodeAkunIds[array_rand($kodeAkunIds)];
            $randomJenis = $jenis[array_rand($jenis)];
            $randomNominal = rand(100000, 1000000);

            // Hitung saldo otomatis
            if ($randomJenis === 'pemasukan') {
                $saldo += $randomNominal;
            } else {
                $saldo -= $randomNominal;
            }

            DB::table('kas')->insert([
                'user_id' => 2,
                'kodeakun_id' => $randomKodeAkun,
                'tanggal' => Carbon::now()->subDays(rand(0, 30))->format('Y-m-d'),
                'jenis' => $randomJenis,
                'nominal' => $randomNominal,
                'keterangan' => 'Transaksi dummy #' . $i,
                'saldo' => $saldo,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
