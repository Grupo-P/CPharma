<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToVueltoVdcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vuelto_vdc', function (Blueprint $table) {
            $table->string('sede')->after('telefono_cliente')->nullable();
            $table->string('caja')->after('sede')->nullable();
            $table->float('monto', 8, 2)->after('caja')->nullable();

            $table->string('confirmacion_banco')->nullable()->change();
            $table->string('motivo_error')->nullable();
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
            $table->dropColumn('sede');
            $table->dropColumn('caja');
            $table->dropColumn('monto');
            $table->dropColumn('motivo_error');
        });
    }
}
