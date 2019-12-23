<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CambiarPuntuacionToRhiCandidatosPruebasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rhi_candidatos_pruebas', function (Blueprint $table) {
            $table->renameColumn('puntuacion', 'resultado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rhi_candidatos_pruebas', function (Blueprint $table) {
            $table->renameColumn('resultado', 'puntuacion');
        });
    }
}
