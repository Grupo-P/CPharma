<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrasladosDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traslados_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_traslado');
            $table->integer('id_articulo');
            $table->string('codigo_interno');
            $table->string('codigo_barra');
            $table->string('descripcion');
            $table->string('gravado');
            $table->string('dolarizado');
            $table->string('cantidad');
            $table->string('costo_unit_bs_sin_iva');
            $table->string('costo_unit_usd_sin_iva');
            $table->string('total_imp_bs');
            $table->string('total_imp_usd');
            $table->string('total_bs');
            $table->string('total_usd');
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
        Schema::dropIfExists('traslados_detalle');
    }
}
