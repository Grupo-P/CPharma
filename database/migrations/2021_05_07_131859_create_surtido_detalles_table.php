<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurtidoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surtido_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('control');
            $table->integer('id_articulo');
            $table->string('codigo_articulo');
            $table->string('codigo_barra');
            $table->string('descripcion');
            $table->string('existencia_actual');
            $table->string('cantidad');
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
        Schema::dropIfExists('surtido_detalles');
    }
}
