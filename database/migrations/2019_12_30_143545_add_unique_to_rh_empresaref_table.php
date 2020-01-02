<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueToRhEmpresarefTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rh_empresaref', function (Blueprint $table) {
            $table->unique('nombre_empresa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rh_empresaref', function (Blueprint $table) {
            $table->dropUnique('rh_empresaref_nombre_empresa_unique');
        });
    }
}
