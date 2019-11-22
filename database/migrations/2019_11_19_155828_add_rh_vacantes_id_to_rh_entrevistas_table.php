<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRhVacantesIdToRhEntrevistasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rh_entrevistas', function (Blueprint $table) {
            $table->unsignedInteger('rh_vacantes_id')->after('rh_candidatos_id');

            $table->foreign('rh_vacantes_id')
                  ->references('id')
                  ->on('rh_vacantes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rh_entrevistas', function (Blueprint $table) {
            $table->dropForeign('rh_entrevistas_rh_vacantes_id_foreign');
        });
    }
}
