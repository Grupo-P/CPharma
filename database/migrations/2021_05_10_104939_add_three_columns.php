<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThreeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surtidos', function (Blueprint $table) {
            $table->string('operador_anulado')->nullable()->after('operador_procesado');
            $table->string('fecha_anulado')->nullable()->after('fecha_procesado');
            $table->string('motivo_anulado')->nullable()->after('fecha_anulado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surtidos', function (Blueprint $table) {
            $table->dropColumn('operador_anulado');
            $table->dropColumn('fecha_anulado');
            $table->dropColumn('motivo_anulado');
        });
    }
}
