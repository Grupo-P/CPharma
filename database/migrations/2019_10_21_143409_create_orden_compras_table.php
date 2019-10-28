<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_compras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo')->unique();
            $table->string('moneda'); 
            $table->string('condicion_crediticia');
            $table->integer('dias_credito')->nullable();
            $table->string('sede_origen');
            $table->string('sede_destino');
            $table->string('proveedor');
            $table->date('fecha_estimada_despacho');
            $table->String('montoTotalBs')->nullable();
            $table->String('montoTotalUsd')->nullable();
            $table->String('totalUnidades')->nullable();
            $table->String('observacion')->nullable();
            $table->String('calificacion')->nullable();
            $table->String('montoTotalReal')->nullable();
            $table->string('operador_aprobacion')->nullable();
            $table->date('fecha_aprobacion')->nullable();
            $table->string('operador_recepcion')->nullable();
            $table->date('fecha_recepcion')->nullable();
            $table->string('operador_ingreso')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->string('operador_cierre')->nullable();
            $table->date('fecha_cierre')->nullable();
            $table->string('estatus');
            $table->string('estado');
            $table->string('user');
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
        Schema::dropIfExists('orden_compras');
    }
}
