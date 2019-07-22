<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCreateCartaCompromisosTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('carta_compromisos', function (Blueprint $table) {
            $table->dropColumn('fecha_vencimiento');
            $table->dropColumn('proveedor');
            $table->dropColumn('fecha_recepcion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('carta_compromisos', function (Blueprint $table) {
            $table->date('fecha_vencimiento')->after('lote');
            $table->string('proveedor')->after('fecha_vencimiento');
            $table->date('fecha_recepcion')->after('proveedor');
        });
    }
}
