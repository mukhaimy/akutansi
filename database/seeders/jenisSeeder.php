<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class jenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jenises')->insert([
            'nama' => 'cair',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('jenises')->insert([
            'nama' => 'alat berat',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('jenises')->insert([
            'nama' => 'gas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
