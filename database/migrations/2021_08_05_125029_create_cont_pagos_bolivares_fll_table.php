<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContPagosBolivaresFllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cont_pagos_bolivares_fll', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_proveedor')->nullable();
            $table->string('id_cuenta')->nullable();
            $table->string('ingresos')->nullable();
            $table->string('egresos')->nullable();
            $table->string('diferido')->nullable();
            $table->string('saldo_anterior');
            $table->string('saldo_actual');
            $table->string('diferido_anterior')->nullable();
            $table->string('diferido_actual')->nullable();
            $table->string('concepto');
            $table->string('user');
            $table->string('user_up')->nullable();
            $table->string('estatus')->nullable();
            $table->string('sede');
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
        Schema::dropIfExists('cont_pagos_bolivares_fll');
    }
}
