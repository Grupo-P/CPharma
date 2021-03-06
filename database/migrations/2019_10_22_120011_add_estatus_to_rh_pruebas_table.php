<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstatusToRhPruebasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rh_pruebas', function (Blueprint $table) {
            $table->string('estatus')->after('nombre_prueba');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rh_pruebas', function (Blueprint $table) {
            $table->dropColumn('estatus');
        });
    }
}
