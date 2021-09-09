<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToContPagosEfectivo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_pagos_efectivo', function (Blueprint $table) {
            $table->string('autorizado_por')->nullable()->after('tasa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cont_pagos_efectivo', function (Blueprint $table) {
            $table->dropColumn('autorizado_por');
        });
    }
}
