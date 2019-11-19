<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRhVacantesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rh_vacantes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_vacante');
            $table->string('departamento');
            $table->string('turno');
            $table->string('dias_libres');
            $table->string('sede');
            $table->unsignedInteger('cantidad');
            $table->date('fecha_solicitud');
            $table->date('fecha_limite');
            $table->string('nivel_urgencia');
            $table->string('solicitante');
            $table->text('comentarios');
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
        Schema::dropIfExists('rh_vacantes');
    }
}
