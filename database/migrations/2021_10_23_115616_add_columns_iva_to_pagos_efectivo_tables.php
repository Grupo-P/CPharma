<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsIvaToPagosEfectivoTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
            $table->string('iva')->nullable();
            $table->string('retencion_deuda_1')->nullable();
            $table->string('retencion_deuda_2')->nullable();
            $table->string('retencion_iva')->nullable();
        });

        Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
            $table->string('iva')->nullable();
            $table->string('retencion_deuda_1')->nullable();
            $table->string('retencion_deuda_2')->nullable();
            $table->string('retencion_iva')->nullable();
        });

        Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
            $table->string('iva')->nullable();
            $table->string('retencion_deuda_1')->nullable();
            $table->string('retencion_deuda_2')->nullable();
            $table->string('retencion_iva')->nullable();
        });

        Schema::table('cont_pagos_efectivo_fau', function (Blueprint $table) {
            $table->string('iva')->nullable();
            $table->string('retencion_deuda_1')->nullable();
            $table->string('retencion_deuda_2')->nullable();
            $table->string('retencion_iva')->nullable();
        });

        Schema::table('cont_pagos_efectivo_ftn', function (Blueprint $table) {
            $table->string('iva')->nullable();
            $table->string('retencion_deuda_1')->nullable();
            $table->string('retencion_deuda_2')->nullable();
            $table->string('retencion_iva')->nullable();
        });

        Schema::table('cont_pagos_efectivo_fll', function (Blueprint $table) {
            $table->string('iva')->nullable();
            $table->string('retencion_deuda_1')->nullable();
            $table->string('retencion_deuda_2')->nullable();
            $table->string('retencion_iva')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
            $table->dropColumn('iva');
            $table->dropColumn('retencion_deuda_1');
            $table->dropColumn('retencion_deuda_2');
            $table->dropColumn('retencion_iva');
        });

        Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
            $table->dropColumn('iva');
            $table->dropColumn('retencion_deuda_1');
            $table->dropColumn('retencion_deuda_2');
            $table->dropColumn('retencion_iva');
        });

        Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
            $table->dropColumn('iva');
            $table->dropColumn('retencion_deuda_1');
            $table->dropColumn('retencion_deuda_2');
            $table->dropColumn('retencion_iva');
        });

        Schema::table('cont_pagos_efectivo_fau', function (Blueprint $table) {
            $table->dropColumn('iva');
            $table->dropColumn('retencion_deuda_1');
            $table->dropColumn('retencion_deuda_2');
            $table->dropColumn('retencion_iva');
        });

        Schema::table('cont_pagos_efectivo_ftn', function (Blueprint $table) {
            $table->dropColumn('iva');
            $table->dropColumn('retencion_deuda_1');
            $table->dropColumn('retencion_deuda_2');
            $table->dropColumn('retencion_iva');
        });

        Schema::table('cont_pagos_efectivo_fll', function (Blueprint $table) {
            $table->dropColumn('iva');
            $table->dropColumn('retencion_deuda_1');
            $table->dropColumn('retencion_deuda_2');
            $table->dropColumn('retencion_iva');
        });
    }
}
