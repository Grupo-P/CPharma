<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToContPagosBancariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
            $table->string('tasa')->after('operador')->nullable();
        });

        Schema::table('cont_pagos_efectivo', function (Blueprint $table) {
            $table->string('tasa')->after('user')->nullable();
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
            Schema::dropIfExists('tasa');
        });

        Schema::table('cont_pagos_efectivo', function (Blueprint $table) {
            Schema::dropIfExists('tasa');
        });
    }
}
