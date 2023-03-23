<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stoks')->insert([
            'lokasi_id' => 1,
            'varietas_id' => 1,
            'masuk_bibit_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('stoks')->insert([
            'lokasi_id' => 2,
            'varietas_id' => 2,
            'masuk_bibit_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('stoks')->insert([
            'lokasi_id' => 3,
            'varietas_id' => 4,
            'masuk_bibit_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
