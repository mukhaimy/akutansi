<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('satuans')->insert([
            'nama' => 'liter',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('satuans')->insert([
            'nama' => 'kilogram',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('satuans')->insert([
            'nama' => 'gram',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('satuans')->insert([
            'nama' => 'ret',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('satuans')->insert([
            'nama' => 'pcs',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('satuans')->insert([
            'nama' => 'batang',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('satuans')->insert([
            'nama' => 'unit',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('satuans')->insert([
            'nama' => 'buah',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('satuans')->insert([
            'nama' => 'lembar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('satuans')->insert([
            'nama' => 'bungkus',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('satuans')->insert([
            'nama' => 'benih (kecambah)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('satuans')->insert([
            'nama' => 'bibit',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
