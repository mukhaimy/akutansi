<?php

namespace Database\Seeders;

use Date;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MasukBibitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $test = Carbon::now()->tz('Asia/Jakarta');

        DB::table('masuk_bibits')->insert([
            'masuk' => Date($test),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
}
