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
            ['penjualan_id' => 1, 'user_id' => 1, 'pembeli' => 'Andi', 'penjualan_kode' => 'PNJ001', 'penjualan_tanggal' => '2024-02-01'],
            ['penjualan_id' => 2, 'user_id' => 2, 'pembeli' => 'Budi', 'penjualan_kode' => 'PNJ002', 'penjualan_tanggal' => '2024-02-02'],
            ['penjualan_id' => 3, 'user_id' => 1, 'pembeli' => 'Citra', 'penjualan_kode' => 'PNJ003', 'penjualan_tanggal' => '2024-02-03'],
            ['penjualan_id' => 4, 'user_id' => 3, 'pembeli' => 'Dewi', 'penjualan_kode' => 'PNJ004', 'penjualan_tanggal' => '2024-02-04'],
            ['penjualan_id' => 5, 'user_id' => 2, 'pembeli' => 'Eko', 'penjualan_kode' => 'PNJ005', 'penjualan_tanggal' => '2024-02-05'],
            ['penjualan_id' => 6, 'user_id' => 1, 'pembeli' => 'Fajar', 'penjualan_kode' => 'PNJ006', 'penjualan_tanggal' => '2024-02-06'],
            ['penjualan_id' => 7, 'user_id' => 3, 'pembeli' => 'Gita', 'penjualan_kode' => 'PNJ007', 'penjualan_tanggal' => '2024-02-07'],
            ['penjualan_id' => 8, 'user_id' => 2, 'pembeli' => 'Hadi', 'penjualan_kode' => 'PNJ008', 'penjualan_tanggal' => '2024-02-08'],
            ['penjualan_id' => 9, 'user_id' => 1, 'pembeli' => 'Indah', 'penjualan_kode' => 'PNJ009', 'penjualan_tanggal' => '2024-02-09'],
            ['penjualan_id' => 10, 'user_id' => 3, 'pembeli' => 'Joko', 'penjualan_kode' => 'PNJ010', 'penjualan_tanggal' => '2024-02-10'],
        ];
        
        DB::table('t_penjualan')->insert($data);
    }
}
