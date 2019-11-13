<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTelefonosToRhLaboratorioTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rh_laboratorio', function (Blueprint $table) {
            $table->string('telefono_fijo')->after('direccion');
            $table->string('telefono_celular')->after('telefono_fijo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rh_laboratorio', function (Blueprint $table) {
            $table->dropColumn('telefono_fijo');
            $table->dropColumn('telefono_celular');
        });
    }
}
