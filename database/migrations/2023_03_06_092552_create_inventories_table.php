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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('nama');

            $table->foreignId('lokasi_id')
                ->constrained('lokasies')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('jenis_id')
                ->constrained('jenises')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('satuan_id')
                ->constrained('satuans')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->double('kuantitas');
            $table->string('keterangan');

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
        Schema::dropIfExists('inventories');
    }
};
