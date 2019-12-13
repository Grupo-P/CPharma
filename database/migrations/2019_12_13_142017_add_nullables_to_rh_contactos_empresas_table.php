<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullablesToRhContactosEmpresasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rh_contactos_empresas', function (Blueprint $table) {
            $table->string('telefono')->nullable()->change();
            $table->string('correo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rh_contactos_empresas', function (Blueprint $table) {
            $table->string('telefono')->change();
            $table->string('correo')->change();
        });
    }
}
