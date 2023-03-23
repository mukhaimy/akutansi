<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bulans')->insert([
            'nama' => 'januari',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bulans')->insert([
            'nama' => 'februari',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bulans')->insert([
            'nama' => 'maret',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bulans')->insert([
            'nama' => 'april',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bulans')->insert([
            'nama' => 'mei',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bulans')->insert([
            'nama' => 'juni',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bulans')->insert([
            'nama' => 'juli',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bulans')->insert([
            'nama' => 'agustus',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bulans')->insert([
            'nama' => 'september',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bulans')->insert([
            'nama' => 'oktober',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bulans')->insert([
            'nama' => 'november',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bulans')->insert([
            'nama' => 'desember',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
