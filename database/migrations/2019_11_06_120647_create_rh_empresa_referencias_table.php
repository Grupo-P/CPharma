<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRhEmpresaReferenciasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rh_empresa_referencias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_empresa');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('correo')->unique()->nullable();
            $table->string('estatus');
            $table->string('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('rh_empresa_referencias');
    }
}
