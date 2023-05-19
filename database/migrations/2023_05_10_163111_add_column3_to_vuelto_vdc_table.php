<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumn3ToVueltoVdcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vuelto_vdc', function (Blueprint $table) {
            $table->string('cedulaClienteFactura')->after('montoPagado')->nullable();
            $table->string('nombreClienteFactura')->after('montoPagado')->nullable();
            $table->string('nombreCajeroFactura')->after('montoPagado')->nullable();
            $table->float('totalFacturaBs', 8, 2)->after('montoPagado')->nullable();
            $table->float('totalFacturaDolar', 8, 2)->after('montoPagado')->nullable();
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
            $table->dropColumn('cedulaClienteFactura');
            $table->dropColumn('nombreClienteFactura');
            $table->dropColumn('nombreCajeroFactura');
            $table->dropColumn('totalFacturaBs');
            $table->dropColumn('totalFacturaDolar');
        });
    }
}
