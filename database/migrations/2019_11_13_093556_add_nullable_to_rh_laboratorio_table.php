<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableToRhLaboratorioTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rh_laboratorio', function (Blueprint $table) {
            $table->string('telefono_fijo')->nullable()->change();
            $table->string('telefono_celular')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rh_laboratorio', function (Blueprint $table) {
            $table->string('telefono_fijo')->change();
            $table->string('telefono_celular')->change();
        });
    }
}
