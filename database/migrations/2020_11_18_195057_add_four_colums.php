<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFourColums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_compra_detalles', function (Blueprint $table) {
            $table->string('unidades_vendidas')->nullable()->after('user');
            $table->string('venta_diaria_real')->nullable()->after('unidades_vendidas');
            $table->string('pedir_real')->nullable()->after('venta_diaria_real');
            $table->string('dias_pedir')->nullable()->after('pedir_real');
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
            $table->dropColumn('unidades_vendidas');
            $table->dropColumn('venta_diaria_real');
            $table->dropColumn('pedir_real');
            $table->dropColumn('dias_pedir');
        });
    }
}
