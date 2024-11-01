<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContPagosBolivaresCddTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cont_pagos_bolivares_cdd', function (Blueprint $table) {
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
            $table->string('fecha_conciliado')->nullable();
            $table->string('usuario_conciliado')->nullable();
            $table->string('tasa')->nullable();
            $table->string('autorizado_por')->nullable();
            $table->string('estatus_conciliaciones')->nullable();
            $table->string('titular_pago')->nullable();
            $table->string('monto_proveedor')->default(0);
            $table->string('iva')->nullable();
            $table->string('retencion_deuda_1')->nullable();
            $table->string('retencion_deuda_2')->nullable();
            $table->string('retencion_iva')->nullable();
            $table->string('monto_banco')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cont_pagos_bolivares_cdd');
    }
}
