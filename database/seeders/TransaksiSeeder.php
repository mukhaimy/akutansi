<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaksies')->insert([
            'nama' => 'debit',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('transaksies')->insert([
            'nama' => 'kredit',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
