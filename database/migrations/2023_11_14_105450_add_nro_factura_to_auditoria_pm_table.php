<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNroFacturaToAuditoriaPmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auditoria_pm', function (Blueprint $table) {
            $table->text('nro_factura')->after('caja');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auditoria_pm', function (Blueprint $table) {
            $table->dropColumn('nro_factura');
        });
    }
}
