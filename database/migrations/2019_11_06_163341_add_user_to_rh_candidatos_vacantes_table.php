<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserToRhCandidatosVacantesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rh_candidatos_vacantes', function (Blueprint $table) {
            $table->string('user')->after('rh_vacantes_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rh_candidatos_vacantes', function (Blueprint $table) {
            $table->dropColumn('user');
        });
    }
}
