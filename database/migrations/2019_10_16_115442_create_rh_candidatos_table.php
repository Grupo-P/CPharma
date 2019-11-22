<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRhCandidatosTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rh_candidatos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('cedula')->unique();
            $table->string('direccion');
            $table->string('telefono_celular')->nullable();
            $table->string('telefono_habitacion')->nullable();
            $table->string('correo')->unique()->nullable();
            $table->string('como_nos_contacto')->nullable();
            $table->string('experiencia_laboral')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('tipo_relacion');
            $table->string('relaciones_laborales');
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
        Schema::dropIfExists('rh_candidatos');
    }
}
