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
            [   'supplier_id' => 1, 
                'supplier_nama' => 'PT. Sumber Makmur', 
                'supplier_alamat' => 'Jl. Merdeka No. 12, Jakarta', 
                'supplier_telepon' => '081234567890', 
                'supplier_email' => 'sumbermakmur@email.com',
            ],
            [   'supplier_id' => 2, 
                'supplier_nama' => 'CV. Maju Jaya', 
                'supplier_alamat' => 'Jl. Raya Sudirman, Bandung', 
                'supplier_telepon' => '082345678901', 
                'supplier_email' => 'majuyaya@email.com',
            ],
            [   'supplier_id' => 3, 
                'supplier_nama' => 'UD. Berkah Sejahtera', 
                'supplier_alamat' => 'Jl. Gajah Mada, Surabaya', 
                'supplier_telepon' => '083456789012', 
                'supplier_email' => 'berkah@email.com',
            ],
            [   'supplier_id' => 4, 
                'supplier_nama' => 'PT. Sentosa Abadi', 
                'supplier_alamat' => 'Jl. Diponegoro, Yogyakarta', 
                'supplier_telepon' => '084567890123', 
                'supplier_email' => 'sentosa@email.com',
            ],
            [   'supplier_id' => 5, 
                'supplier_nama' => 'CV. Cahaya Baru', 
                'supplier_alamat' => 'Jl. Pahlawan, Semarang', 
                'supplier_telepon' => '085678901234', 
                'supplier_email' => 'cahayabaru@email.com',
            ],
        ];        
        DB::table('m_supplier')->insert($data);        
    }
}
