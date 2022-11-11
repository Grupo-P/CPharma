<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticulosVencerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos_vencer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_articulo');
            $table->string('codigo');
            $table->string('codigo_barra');
            $table->string('descripcion');
            $table->string('precio_iva_bs');
            $table->string('dias_restantes_30');
            $table->string('dias_riesgo');
            $table->string('ultima_compra');
            $table->string('fecha_lote');
            $table->string('fecha_vencimiento');
            $table->string('vida_util');
            $table->string('dias_para_vencer');
            $table->string('existencia_total');
            $table->string('existencia_lote');
            $table->string('valor_lote_bs');
            $table->string('valor_lote_ds');
            $table->string('numero_lote');
            $table->string('lote_fabricante');
            $table->string('tipo');
            $table->string('dolarizado');
            $table->string('gravado');
            $table->string('clasificacion');
            $table->string('ultima_venta');
            $table->string('ultimo_proveedor_nombre');
            $table->string('ultimo_proveedor_id');
            $table->string('descripcion_sede_1');
            $table->string('existencia_sede_1');
            $table->string('descripcion_sede_2');
            $table->string('existencia_sede_2');
            $table->string('descripcion_sede_3');
            $table->string('existencia_sede_3');
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
        Schema::dropIfExists('articulos_vencer');
    }
}
