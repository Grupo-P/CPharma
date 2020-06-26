<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo')->unique();
            $table->string('origen_conteo')->nullable();
            $table->string('motivo_conteo')->nullable();
            $table->string('cantidades_conteo')->nullable();
            $table->string('unidades_conteo')->nullable();
            $table->string('estatus');
            $table->string('operador_generado')->nullable();
            $table->timestamp('fecha_generado')->nullable();
            $table->string('operador_anulado')->nullable();
            $table->timestamp('fecha_anulado')->nullable();
            $table->string('operador_revisado')->nullable();
            $table->timestamp('fecha_revisado')->nullable();
            $table->string('comentario')->nullable();
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
        Schema::dropIfExists('inventarios');
    }
}
