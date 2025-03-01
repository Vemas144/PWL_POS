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
            [   
                'barang_id' => 1, 'kategori_id' => 1, 
                'barang_kode' => 'ELEC001', 'barang_nama' => 'TV LED 32 Inch', 
                'harga_beli' => 900000, 'harga_jual' => 1000000,
            ],
            [   
                'barang_id' => 2, 'kategori_id' => 1,
                'barang_kode' => 'ELEC002', 'barang_nama' => 'Smartphone',
                'harga_beli' => 300000, 'harga_jual' => 350000,
            ],
            [   
                'barang_id' => 3, 'kategori_id' => 2,
                'barang_kode' => 'AUTO001', 'barang_nama' => 'Oli Mesin',
                'harga_beli' => 250000, 'harga_jual' => 300000,
            ],
            [   
                'barang_id' => 4, 'kategori_id' => 2, 
                'barang_kode' => 'AUTO002', 'barang_nama' => 'Spion Motor', 
                'harga_beli' => 150000, 'harga_jual' => 200000,
            ],
            [   
                'barang_id' => 5, 'kategori_id' => 3, 
                'barang_kode' => 'CLOTH001', 'barang_nama' => 'Kaos Polos', 
                'harga_beli' => 100000, 'harga_jual' => 150000,
            ],
            [   
                'barang_id' => 6, 'kategori_id' => 3, 
                'barang_kode' => 'CLOTH002', 'barang_nama' => 'Celana Jeans',
                'harga_beli' => 250000, 'harga_jual' => 300000,
            ],
            [   
                'barang_id' => 7, 'kategori_id' => 4, 
                'barang_kode' => 'FOOD001', 'barang_nama' => 'Susu UHT 1L', 
                'harga_beli' => 50000, 'harga_jual' => 75000,
            ],
            [   
                'barang_id' => 8, 'kategori_id' => 4, 
                'barang_kode' => 'FOOD002', 'barang_nama' => 'Minyak Goreng 2L', 
                'harga_beli' => 200000, 'harga_jual' => 250000,
            ],
            [   
                'barang_id' => 9, 'kategori_id' => 5, 
                'barang_kode' => 'TOY001', 'barang_nama' => 'Boneka Teddy Bear', 
                'harga_beli' => 150000, 'harga_jual' => 200000,
            ],
            [   
                'barang_id' => 10, 'kategori_id' => 5, 
                'barang_kode' => 'TOY002', 'barang_nama' => 'Mobil Remote', 
                'harga_beli' => 100000, 'harga_jual' => 150000,
            ],
        ];
        
        DB::table('m_barang')->insert($data);
    }
}
