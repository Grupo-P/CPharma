<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtiquetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etiquetas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_articulo');
            $table->string('codigo_articulo');
            $table->string('descripcion');
            $table->string('condicion'); // CLASIFICADO, NO CLASIFICADO
            $table->string('clasificacion'); //NO ETIQUETABLE,ETIQUETABLES,OBLIGATORIO
            $table->string('estatus'); //ACTIVO,INACTIVO
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
        Schema::dropIfExists('etiquetas');
    }
}
