<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeDataToRhCandidatosTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rh_candidatos', function (Blueprint $table) {
            $table->text('direccion')->after('cedula')->change();

            $table->text('experiencia_laboral')
            ->after('como_nos_contacto')
            ->nullable()
            ->change();

            $table->text('observaciones')
            ->after('experiencia_laboral')
            ->nullable()
            ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rh_candidatos', function (Blueprint $table) {
            $table->string('direccion')->after('cedula')->change();

            $table->string('experiencia_laboral')
            ->after('como_nos_contacto')
            ->nullable()
            ->change();

            $table->string('observaciones')
            ->after('experiencia_laboral')
            ->nullable()
            ->change();
        });
    }
}
