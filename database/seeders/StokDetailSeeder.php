<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StokDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stok_details')->insert([
            'stok_id' => 1,
            'keterangan_bibit_id' => 1,
            'kuantitas' => 200,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('stok_details')->insert([
            'stok_id' => 1,
            'keterangan_bibit_id' => 1,
            'kuantitas' => 200,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('stoks')->insert([
            'stok_id' => 1,
            'keterangan_bibit_id' => 1,
            'kuantitas' => 200,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
