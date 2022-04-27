<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTrasladosDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traslados_detalle', function (Blueprint $table) {
            $table->string('causa')->after('descripcion')->nullable();

            $table->string('id_traslado')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('traslados_detalle', function (Blueprint $table) {
            $table->dropColumn('causa');

            $table->integer('id_traslado')->change();
        });
    }
}
