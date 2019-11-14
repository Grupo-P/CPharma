<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRhContactosEmpresasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rh_contactos_empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono');
            $table->string('correo');
            $table->string('cargo');
            $table->string('user');
            $table->string('estatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('rh_contactos_empresas');
    }
}
