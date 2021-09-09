<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPagosTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
            $table->string('estatus')->after('operador')->nullable();
        });

        Schema::table('cont_pagos_efectivo', function (Blueprint $table) {
            $table->string('estatus_conciliaciones')->after('user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
            $table->dropColumn('estatus');
        });

        Schema::table('cont_pagos_efectivo', function (Blueprint $table) {
            $table->dropColumn('estatus_conciliaciones');
        });
    }
}
