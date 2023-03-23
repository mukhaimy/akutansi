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
        Schema::create('stoks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lokasi_id')
                ->constrained('lokasies')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('varietas_id')
                ->constrained('varietases')->nullable()->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('masuk_bibit_id')
                ->constrained('masuk_bibits')->nullable()->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('stoks');
    }
};
