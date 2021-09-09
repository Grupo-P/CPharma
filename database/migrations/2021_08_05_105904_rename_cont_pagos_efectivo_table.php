<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameContPagosEfectivoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_pagos_efectivo', function (Blueprint $table) {
            $table->dropColumn('tasa');
            $table->dropColumn('autorizado_por');
            $table->dropColumn('estatus_conciliaciones');
        });

        Schema::rename('cont_pagos_efectivo', 'cont_pagos_efectivo_ftn');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('cont_pagos_efectivo_ftn', 'cont_pagos_efectivo');

        Schema::table('cont_pagos_efectivo', function (Blueprint $table) {
            $table->string('tasa')->after('user')->nullable();
            $table->string('autorizado_por')->nullable()->after('tasa');
            $table->string('estatus_conciliaciones')->nullable();
        });
    }
}
