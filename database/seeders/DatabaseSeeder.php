<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            TahunSeeder::class,
            BulanSeeder::class,
            HariSeeder::class,
            LokasiSeeder::class,
            SatuanSeeder::class,
            BarangSeeder::class,
            TransaksiSeeder::class,
            VarietasSeeder::class,
            KeteranganBibitSeeder::class,
            JenisSeeder::class,
            // MasukBibitSeeder::class,
            // StokSeeder::class,
            // StokDetailSeeder::class,
            // DetailSeeder::class,
            // NotaSeeder::class,
            ObatSeeder::class,
        ]);
    }
}
