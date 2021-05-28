<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContPagosBancariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cont_pagos_bancarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_proveedor');
            $table->string('id_banco');
            $table->string('monto');
            $table->text('comentario')->nullable();
            $table->string('operador');
            $table->softDeletes();
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
        Schema::dropIfExists('cont_pagos_bancarios');
    }
}
