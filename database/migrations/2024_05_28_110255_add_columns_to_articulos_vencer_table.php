<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToArticulosVencerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articulos_vencer', function (Blueprint $table) {
            $table->string('descripcion_sede_5')->after('existencia_sede_4');
            $table->string('existencia_sede_5')->after('descripcion_sede_5');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articulos_vencer', function (Blueprint $table) {
            $table->dropColumn('descripcion_sede_5');
            $table->dropColumn('existencia_sede_5');
        });
    }
}
