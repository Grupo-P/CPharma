<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategorizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorizacions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_articulo');
            $table->string('codigo_interno');
            $table->string('codigo_barra');
            $table->string('descripcion');
            $table->string('marca');
            $table->string('codigo_categoria');
            $table->string('codigo_subcategoria');                    
            $table->string('estatus');
            $table->String('user');
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
        Schema::dropIfExists('categorizacions');
    }
}
