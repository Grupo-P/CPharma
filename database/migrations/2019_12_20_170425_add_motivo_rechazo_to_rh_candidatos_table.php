<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMotivoRechazoToRhCandidatosTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rh_candidatos', function (Blueprint $table) {
            $table->text('motivo_rechazo')->nullable()->after('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rh_candidatos', function (Blueprint $table) {
            $table->dropColumn('motivo_rechazo');
        });
    }
}
