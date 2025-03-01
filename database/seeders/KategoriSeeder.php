<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kategori_id' => 1, 'kategori_kode' => 'ELEC', 'kategori_nama' => 'Elektronik'],
            ['kategori_id' => 2, 'kategori_kode' => 'AUTO', 'kategori_nama' => 'Otomotif'],
            ['kategori_id' => 3, 'kategori_kode' => 'CLOTH', 'kategori_nama' => 'Pakaian'],
            ['kategori_id' => 4, 'kategori_kode' => 'FOOD', 'kategori_nama' => 'Makanan & Minuman'],
            ['kategori_id' => 5, 'kategori_kode' => 'TOY', 'kategori_nama' => 'Mainan'],
        ];
        
        DB::table('m_kategori')->insert($data);
    }
}
