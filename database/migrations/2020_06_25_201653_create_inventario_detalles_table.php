<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventarioDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventario_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo_conteo');
            $table->integer('id_articulo');
            $table->string('codigo_articulo');
            $table->string('codigo_barra');
            $table->string('descripcion');
            $table->string('existencia_actual');            
            $table->string('conteo')->nullable();
            $table->string('re_conteo')->nullable();
            $table->string('operador_conteo')->nullable();
            $table->timestamp('fecha_conteo')->nullable();
            $table->string('operador_reconteo')->nullable();
            $table->timestamp('fecha_reconteo')->nullable();
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
        Schema::dropIfExists('inventario_detalles');
    }
}
