<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelacionesToRhContactosEmpresasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rh_contactos_empresas', function (Blueprint $table) {
            $table->unsignedInteger('rh_emprf_id')->after('id');

            $table->foreign('rh_emprf_id')
            ->references('id')
            ->on('rh_empresaRef')
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
        Schema::table('rh_contactos_empresas', function (Blueprint $table) {
            $table->dropForeign('rh_contactos_empresas_rh_emprf_id_foreign');
            $table->dropColumn('rh_emprf_id');
        });
    }
}
