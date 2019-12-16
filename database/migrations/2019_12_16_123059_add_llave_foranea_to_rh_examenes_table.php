<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLlaveForaneaToRhExamenesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rh_examenes', function (Blueprint $table) {
            $table->dropColumn('representante');
            $table->unsignedInteger('rh_candidatos_id')->after('id');

            $table->foreign('rh_candidatos_id')
            ->references('id')
            ->on('rh_candidatos')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rh_examenes', function (Blueprint $table) {
            $table->string('representante')->after('empresa');
            $table->dropForeign('rh_examenes_rh_candidatos_id_foreign');
            $table->dropColumn('rh_candidatos_id');
        });
    }
}
