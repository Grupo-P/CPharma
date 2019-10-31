<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVariablesToOrdenDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_compra_detalles', function (Blueprint $table) {
            $table->string('estatus');
            $table->string('user');
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
            $table->dropColumn('estatus');
            $table->dropColumn('user');
        });
    }
}
