<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tahun_id')
                ->constrained('tahuns')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('bulan_id')
                ->constrained('bulans')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('hari_id')
                ->constrained('haries')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('lokasi_id')
                ->constrained('lokasies')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('barang_id')
                ->constrained('barangs')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('satuan_id')
                ->constrained('satuans')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->double('kuantitas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('obats');
    }
};
