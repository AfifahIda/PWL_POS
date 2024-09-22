<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['penjualan_id' => 1, 'user_id' => 1, 'penjualan_kode' => 'TRK001', 'pembeli' => 'Afifah', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 2, 'user_id' => 2, 'penjualan_kode' => 'TRK002', 'pembeli' => 'Nail', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 3, 'user_id' => 3, 'penjualan_kode' => 'TRK003', 'pembeli' => 'Alyssa', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 4, 'user_id' => 1, 'penjualan_kode' => 'TRK004', 'pembeli' => 'Farel', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 5, 'user_id' => 2, 'penjualan_kode' => 'TRK005', 'pembeli' => 'Bagus', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 6, 'user_id' => 3, 'penjualan_kode' => 'TRK006', 'pembeli' => 'Rakha', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 7, 'user_id' => 1, 'penjualan_kode' => 'TRK007', 'pembeli' => 'Dyalifia', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 8, 'user_id' => 2, 'penjualan_kode' => 'TRK008', 'pembeli' => 'Fannisa', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 9, 'user_id' => 3, 'penjualan_kode' => 'TRK009', 'pembeli' => 'Farhan', 'penjualan_tanggal' => now()],
            ['penjualan_id' => 10, 'user_id' => 1, 'penjualan_kode' => 'TRK010', 'pembeli' => 'Dona', 'penjualan_tanggal' => now()],
        ];
        DB::table('t_penjualan')->insert($data);
    }
}
