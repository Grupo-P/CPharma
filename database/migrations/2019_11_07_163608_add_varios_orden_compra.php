<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVariosOrdenCompra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_compras', function (Blueprint $table) {
            $table->dropColumn('montoTotalBs');
            $table->dropColumn('montoTotalUsd');
            $table->renameColumn('totalUnidades', 'numero_factura');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orden_compras', function (Blueprint $table) {
            Schema::dropIfExists('orden_compras');
        });
    }
}
