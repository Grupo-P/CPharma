<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFechaFacilitadorResultadoToRhiCandidatosPruebasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rhi_candidatos_pruebas', function (Blueprint $table) {
            $table->date('fecha')->after('rh_pruebas_id');
            $table->string('facilitador')->after('fecha');
            $table->double('puntuacion', 8, 2)->after('facilitador');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rhi_candidatos_pruebas', function (Blueprint $table) {
            $table->dropColumn([
                'fecha', 
                'facilitador', 
                'puntuacion'
            ]);
        });
    }
}
