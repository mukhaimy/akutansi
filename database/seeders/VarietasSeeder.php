<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VarietasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('varietases')->insert([
            'nama' => 'SJ5',
            'penyedia' => 'PT. Binasawit Makmur',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('varietases')->insert([
            'nama' => 'SJ3',
            'penyedia' => 'PT. Binasawit Makmur',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('varietases')->insert([
            'nama' => 'TN1',
            'penyedia' => 'PT. Bakti Tani Nusantara',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('varietases')->insert([
            'nama' => 'Simalungun',
            'penyedia' => 'PT. Gunung Sejahtera Ibu Pertiwi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
