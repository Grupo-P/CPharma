<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cont_proveedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_proveedor');
            $table->string('nombre_representante')->nullable();
            $table->string('rif_ci')->nullable();
            $table->string('direccion')->nullable();
            $table->string('tasa')->nullable();
            $table->string('plan_cuentas')->nullable();
            $table->string('moneda')->nullable();
            $table->string('saldo')->default('0');
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
        Schema::dropIfExists('cont_proveedores');
    }
}
