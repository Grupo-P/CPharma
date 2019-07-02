<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartaCompromisosTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('carta_compromisos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('articulo');
            $table->string('lote');
            $table->timestamp('fecha de vencimiento');
            $table->string('proveedor');
            $table->timestamp('fecha de recepcion');
            $table->timestamp('fecha de tope');
            $table->string('causa');
            $table->string('nota');
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
        Schema::dropIfExists('carta_compromisos');
    }
}
