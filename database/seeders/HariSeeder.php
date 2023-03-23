<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HariSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 31; $i++) {
            DB::table('haries')->insert([
                'nama' => (string) $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
