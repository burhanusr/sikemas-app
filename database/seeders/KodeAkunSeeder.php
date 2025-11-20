<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KodeAkun;

class KodeAkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            //  SALDO
            ['user_id' => 2, 'kode_akun' => '1001', 'nama_akun' => 'Kas Besar', 'kategori_akun' => 'Saldo'],
            ['user_id' => 2, 'kode_akun' => '1002', 'nama_akun' => 'Kas Kecil', 'kategori_akun' => 'Saldo'],
            ['user_id' => 2, 'kode_akun' => '1003', 'nama_akun' => 'Bank BSI', 'kategori_akun' => 'Saldo'],
            ['user_id' => 2, 'kode_akun' => '1004', 'nama_akun' => 'Bank BCA', 'kategori_akun' => 'Saldo'],
            ['user_id' => 2, 'kode_akun' => '1005', 'nama_akun' => 'Rekening Donasi', 'kategori_akun' => 'Saldo'],
            ['user_id' => 2, 'kode_akun' => '1006', 'nama_akun' => 'Rekening Operasional', 'kategori_akun' => 'Saldo'],
            ['user_id' => 2, 'kode_akun' => '1007', 'nama_akun' => 'Saldo Awal Bulan', 'kategori_akun' => 'Saldo'],

            //  PEMASUKAN
            ['user_id' => 2, 'kode_akun' => '2001', 'nama_akun' => 'Infaq Harian', 'kategori_akun' => 'Pemasukan'],
            ['user_id' => 2, 'kode_akun' => '2002', 'nama_akun' => 'Donasi Jumat', 'kategori_akun' => 'Pemasukan'],
            ['user_id' => 2, 'kode_akun' => '2003', 'nama_akun' => 'Donasi Pembangunan', 'kategori_akun' => 'Pemasukan'],
            ['user_id' => 2, 'kode_akun' => '2004', 'nama_akun' => 'Sumbangan Kegiatan', 'kategori_akun' => 'Pemasukan'],
            ['user_id' => 2, 'kode_akun' => '2005', 'nama_akun' => 'Donasi Khusus', 'kategori_akun' => 'Pemasukan'],
            ['user_id' => 2, 'kode_akun' => '2006', 'nama_akun' => 'Zakat', 'kategori_akun' => 'Pemasukan'],
            ['user_id' => 2, 'kode_akun' => '2007', 'nama_akun' => 'Wakaf', 'kategori_akun' => 'Pemasukan'],

            //  PENGELUARAN
            ['user_id' => 2, 'kode_akun' => '3001', 'nama_akun' => 'Beban Listrik', 'kategori_akun' => 'Pengeluaran'],
            ['user_id' => 2, 'kode_akun' => '3002', 'nama_akun' => 'Beban Air', 'kategori_akun' => 'Pengeluaran'],
            ['user_id' => 2, 'kode_akun' => '3003', 'nama_akun' => 'Beban Kebersihan', 'kategori_akun' => 'Pengeluaran'],
            ['user_id' => 2, 'kode_akun' => '3004', 'nama_akun' => 'Beban Perbaikan', 'kategori_akun' => 'Pengeluaran'],
            ['user_id' => 2, 'kode_akun' => '3005', 'nama_akun' => 'Beban Konsumsi', 'kategori_akun' => 'Pengeluaran'],
            ['user_id' => 2, 'kode_akun' => '3006', 'nama_akun' => 'Beban Acara', 'kategori_akun' => 'Pengeluaran'],
        ];

        foreach ($data as $item) {
            KodeAkun::create($item);
        }
    }
}
