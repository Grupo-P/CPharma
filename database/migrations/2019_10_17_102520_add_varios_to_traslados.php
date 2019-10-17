<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVariosToTraslados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traslados', function (Blueprint $table) {
            $table->string('numero_ajuste')->unique()->change();
            $table->string('bultos')->after('estatus');
            $table->string('tasa')->after('bultos');
            $table->string('fecha_tasa')->after('tasa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('traslados', function (Blueprint $table) {
            //
        });
    }
}
