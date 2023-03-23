<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('barangs')->insert([
            'nama' => 'Barang 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('barangs')->insert([
            'nama' => 'Barang 2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('barangs')->insert([
            'nama' => 'Barang 3',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
