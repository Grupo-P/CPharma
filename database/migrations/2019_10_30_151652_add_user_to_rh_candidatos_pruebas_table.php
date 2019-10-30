<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserToRhCandidatosPruebasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rh_candidatos_pruebas', function (Blueprint $table) {
            $table->string('user')->after('rh_pruebas_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rh_candidatos_pruebas', function (Blueprint $table) {
            $table->dropColumn('user');
        });
    }
}
