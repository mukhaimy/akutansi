<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tahuns')->insert([
            'nama' => '2022',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tahuns')->insert([
            'nama' => '2023',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tahuns')->insert([
            'nama' => '2024',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
