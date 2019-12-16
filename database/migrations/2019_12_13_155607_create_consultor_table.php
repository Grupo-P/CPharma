<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultor', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_articulo');
            $table->string('codigo_interno');
            $table->string('codigo_barra');
            $table->string('descripcion');
            $table->string('precio');
            $table->string('nombre_maquina');
            $table->date('fecha_captura');
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
        Schema::dropIfExists('consultor');
    }
}
