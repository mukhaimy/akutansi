<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('obats')->insert([
            'tahun_id' => 2,
            'bulan_id' => 1,
            'hari_id' => 2,
            'lokasi_id' => 2,
            'barang_id' => 1,
            'satuan_id' => 1,
            'kuantitas' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('obats')->insert([
            'tahun_id' => 2,
            'bulan_id' => 1,
            'hari_id' => 3,
            'lokasi_id' => 1,
            'barang_id' => 2,
            'satuan_id' => 2,
            'kuantitas' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('obats')->insert([
            'tahun_id' => 2,
            'bulan_id' => 1,
            'hari_id' => 4,
            'lokasi_id' => 1,
            'barang_id' => 3,
            'satuan_id' => 1,
            'kuantitas' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('obats')->insert([
            'tahun_id' => 2,
            'bulan_id' => 1,
            'hari_id' => 5,
            'lokasi_id' => 1,
            'barang_id' => 1,
            'satuan_id' => 2,
            'kuantitas' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('obats')->insert([
            'tahun_id' => 2,
            'bulan_id' => 1,
            'hari_id' => 6,
            'lokasi_id' => 1,
            'barang_id' => 2,
            'satuan_id' => 1,
            'kuantitas' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
