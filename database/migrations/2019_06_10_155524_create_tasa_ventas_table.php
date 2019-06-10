<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasaVentasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tasa_ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->float('tasa',8,2);
            $table->string('moneda');
            $table->timestamp('fecha');
            $table->string('estatus');
            $table->string('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tasa_ventas');
    }
}
