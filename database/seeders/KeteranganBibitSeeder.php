<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeteranganBibitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('keterangan_bibits')->insert([
            'nama' => 'Bagus',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('keterangan_bibits')->insert([
            'nama' => 'Karantina',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('keterangan_bibits')->insert([
            'nama' => 'Mati',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('keterangan_bibits')->insert([
            'nama' => 'Lain',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
