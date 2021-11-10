<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContPrepagadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cont_prepagados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_proveedor');
            $table->string('monto')->default(0);
            $table->string('monto_iva')->default(0);
            $table->string('status')->default('Pendiente');
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
        Schema::dropIfExists('cont_prepagados');
    }
}
