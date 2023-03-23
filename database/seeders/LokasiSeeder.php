<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lokasies')->insert([
            'nama' => 'nursery hajak',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('lokasies')->insert([
            'nama' => 'nursery sosial',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('lokasies')->insert([
            'nama' => 'nursery trinsing',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
