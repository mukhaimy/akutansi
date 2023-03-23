<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'gun',
            'role' => 'approver',
            'email' => 'gun@123',
            'password' => Hash::make('12341234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'deny',
            'role' => 'admin',
            'email' => 'deny@123',
            'password' => Hash::make('12341234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
