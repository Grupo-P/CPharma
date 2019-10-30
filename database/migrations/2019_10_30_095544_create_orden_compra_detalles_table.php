<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenCompraDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     *  $table->string('sede1'); -> FTN
        $table->string('sede2'); -> FLL
        $table->string('sede3'); -> FAU
        $table->string('sede4'); -> MC
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_compra_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo_orden');
            $table->integer('id_articulo');
            $table->string('codigo_articulo');
            $table->string('codigo_barra');
            $table->string('descripcion');
            $table->string('existencia_actual');
            $table->string('sede1');
            $table->string('sede2');
            $table->string('sede3');
            $table->string('sede4');
            $table->string('total_unidades');
            $table->string('costo_unitario');
            $table->string('costo_total');
            $table->string('existencia_rpt');
            $table->string('dias_restantes_rpt');
            $table->string('origen_rpt');
            $table->string('rango_rpt');
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
        Schema::dropIfExists('orden_compra_detalles');
    }
}
