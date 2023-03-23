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
        Schema::create('details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('nota_id')
                ->constrained('notas')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('barang_id')
                ->constrained('barangs')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('satuan_id')
                ->constrained('satuans')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('harga_id')
                ->constrained('hargas')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('transaksi_id')
                ->constrained('transaksies')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->double('kuantitas');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('details');
    }
};
