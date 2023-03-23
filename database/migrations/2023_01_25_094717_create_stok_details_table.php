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
        Schema::create('stok_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stok_id')
                ->constrained('stoks')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('keterangan_bibit_id')
                ->constrained('keterangan_bibits')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->integer('kuantitas');
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
        Schema::dropIfExists('stok_details');
    }
};
