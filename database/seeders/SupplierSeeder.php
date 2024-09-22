<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [ 
                'supplier_id' => 1,
                'supplier_kode' => 'S1',
                'supplier_nama' => 'Supplier 1',
                'supplier_alamat' => 'Jl. Soekarno Hatta',
                'supplier_telepon' => '0123456789',
            ],
            [ 
                'supplier_id' => 2,
                'supplier_kode' => 'S2',
                'supplier_nama' => 'Supplier 2',
                'supplier_alamat' => 'Jl. Mayjend Panjaitan',
                'supplier_telepon' => '012332145654',
            ],
            [ 
                'supplier_id' => 3,
                'supplier_kode' => 'S3',
                'supplier_nama' => 'Supplier 3',
                'supplier_alamat' => 'Jl. Veteran',
                'supplier_telepon' => '08755117121',
            ],
        ];
        DB::table('m_supplier')->insert($data);
    }
}
