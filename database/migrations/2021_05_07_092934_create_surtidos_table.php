<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurtidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surtidos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('control')->unique();
            $table->string('sku')->nullable();
            $table->string('unidades')->nullable();
            $table->string('estatus');
            $table->string('operador_generado')->nullable();
            $table->string('operador_procesado')->nullable();
            $table->timestamp('fecha_generado')->nullable();
            $table->timestamp('fecha_procesado')->nullable();
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
        Schema::dropIfExists('surtidos');
    }
}
