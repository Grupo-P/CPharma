<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNuleablesToOrdenDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_compra_detalles', function (Blueprint $table) {
            $table->string('id_articulo')->nullable()->change();
            $table->string('codigo_articulo')->nullable()->change();
            $table->string('codigo_barra')->nullable()->change();
            $table->dropColumn('existencia_actual');
            $table->string('sede1')->nullable()->change();
            $table->string('sede2')->nullable()->change();
            $table->string('sede3')->nullable()->change();
            $table->string('sede4')->nullable()->change();
            $table->string('existencia_rpt')->nullable()->change();
            $table->string('dias_restantes_rpt')->nullable()->change();
            $table->string('origen_rpt')->nullable()->change();
            $table->string('rango_rpt')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orden_compra_detalles', function (Blueprint $table) {
            //
        });
    }
}
