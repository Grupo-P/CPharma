<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVueltoVdcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vuelto_vdc', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('fecha_hora');
            $table->string('id_factura');
            $table->string('banco_cliente');
            $table->string('cedula_cliente');
            $table->string('telefono_cliente');
            $table->string('estatus');
            $table->string('confirmacion_banco');
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
        Schema::dropIfExists('vueltos');
    }
}
