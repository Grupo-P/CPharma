<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumn2ToVueltoVdcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vuelto_vdc', function (Blueprint $table) {
            $table->float('tasaVenta', 8, 2)->after('monto')->nullable();
            $table->float('montoPagado', 8, 2)->after('monto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vuelto_vdc', function (Blueprint $table) {
            $table->dropColumn('tasaVenta');
            $table->dropColumn('montoPagado');
        });
    }
}
