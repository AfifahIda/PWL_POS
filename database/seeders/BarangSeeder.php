<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Supplier 1
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'BRG001', 'barang_nama' => 'Barang A1', 'harga_beli' => 10000, 'harga_jual' => 15000],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'BRG002', 'barang_nama' => 'Barang A2', 'harga_beli' => 12000, 'harga_jual' => 17000],
            ['barang_id' => 3, 'kategori_id' => 1, 'barang_kode' => 'BRG003', 'barang_nama' => 'Barang A3', 'harga_beli' => 9000, 'harga_jual' => 14000],
            ['barang_id' => 4, 'kategori_id' => 1, 'barang_kode' => 'BRG004', 'barang_nama' => 'Barang A4', 'harga_beli' => 13000, 'harga_jual' => 18000],
            ['barang_id' => 5, 'kategori_id' => 1, 'barang_kode' => 'BRG005', 'barang_nama' => 'Barang A5', 'harga_beli' => 15000, 'harga_jual' => 20000],

            // Supplier 2
            ['barang_id' => 6, 'kategori_id' => 2, 'barang_kode' => 'BRG006', 'barang_nama' => 'Barang B1', 'harga_beli' => 11000, 'harga_jual' => 16000],
            ['barang_id' => 7, 'kategori_id' => 2, 'barang_kode' => 'BRG007', 'barang_nama' => 'Barang B2', 'harga_beli' => 14000, 'harga_jual' => 19000],
            ['barang_id' => 8, 'kategori_id' => 2, 'barang_kode' => 'BRG008', 'barang_nama' => 'Barang B3', 'harga_beli' => 12500, 'harga_jual' => 17500],
            ['barang_id' => 9, 'kategori_id' => 2, 'barang_kode' => 'BRG009', 'barang_nama' => 'Barang B4', 'harga_beli' => 16000, 'harga_jual' => 21000],
            ['barang_id' => 10, 'kategori_id' => 2, 'barang_kode' => 'BRG010', 'barang_nama' => 'Barang B5', 'harga_beli' => 18000, 'harga_jual' => 23000],

            // Supplier 3
            ['barang_id' => 11, 'kategori_id' => 3, 'barang_kode' => 'BRG011', 'barang_nama' => 'Barang C1', 'harga_beli' => 9000, 'harga_jual' => 13000],
            ['barang_id' => 12, 'kategori_id' => 3, 'barang_kode' => 'BRG012', 'barang_nama' => 'Barang C2', 'harga_beli' => 9500, 'harga_jual' => 14000],
            ['barang_id' => 13, 'kategori_id' => 3, 'barang_kode' => 'BRG013', 'barang_nama' => 'Barang C3', 'harga_beli' => 10000, 'harga_jual' => 15000],
            ['barang_id' => 14, 'kategori_id' => 3, 'barang_kode' => 'BRG014', 'barang_nama' => 'Barang C4', 'harga_beli' => 10500, 'harga_jual' => 16000],
            ['barang_id' => 15, 'kategori_id' => 3, 'barang_kode' => 'BRG015', 'barang_nama' => 'Barang C5', 'harga_beli' => 11000, 'harga_jual' => 17000],
        ];
        DB::table('m_barang')->insert($data);
    }
}
