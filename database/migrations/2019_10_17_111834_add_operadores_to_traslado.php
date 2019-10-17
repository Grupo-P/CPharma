<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOperadoresToTraslado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traslado', function (Blueprint $table) {
            $table->string('operador_ajuste')->after('fecha_ajuste')->change();
            $table->string('operador_traslado')->after('fecha_traslado')->change();
            $table->string('fecha_embalaje')->after('bultos')->nullable();
            $table->string('operador_embalaje')->after('fecha_embalaje')->nullable();
            $table->string('fecha_envio')->after('operador_embalaje')->nullable();
            $table->string('operador_envio')->after('fecha_envio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('traslado', function (Blueprint $table) {
            //
        });
    }
}
