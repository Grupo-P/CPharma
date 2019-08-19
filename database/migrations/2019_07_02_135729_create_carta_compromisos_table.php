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
        Schema::create('carta_compromisos', function (Blueprint $table)    {
            $table->increments('id');
            $table->string('proveedor');
            $table->string('articulo');
            $table->string('lote');
            $table->date('fecha_documento');
            $table->date('fecha_recepcion');
            $table->date('fecha_vencimiento')->nullable();
            $table->date('fecha_tope');
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
