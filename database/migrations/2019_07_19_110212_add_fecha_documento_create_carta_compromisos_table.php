<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFechaDocumentoCreateCartaCompromisosTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('carta_compromisos', function (Blueprint $table) {
            $table->string('proveedor')->after('id');
            $table->date('fecha_documento')->after('lote');
            $table->date('fecha_recepcion')->after('fecha_documento');
            $table->date('fecha_vencimiento')->nullable()->after('fecha_recepcion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('carta_compromisos', function (Blueprint $table) {
            $table->dropColumn('proveedor');
            $table->dropColumn('fecha_documento');
            $table->dropColumn('fecha_recepcion');
            $table->dropColumn('fecha_vencimiento');
        });
    }
}
