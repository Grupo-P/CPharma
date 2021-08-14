<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitularPagoColumnsToPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_pagos_efectivo_fau', function (Blueprint $table) {
            $table->string('titular_pago')->nullable();
        });

        Schema::table('cont_pagos_efectivo_fll', function (Blueprint $table) {
            $table->string('titular_pago')->nullable();
        });

        Schema::table('cont_pagos_efectivo_ftn', function (Blueprint $table) {
            $table->string('titular_pago')->nullable();
        });

        Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
            $table->string('titular_pago')->nullable();
        });

        Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
            $table->string('titular_pago')->nullable();
        });

        Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
            $table->string('titular_pago')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('cont_pagos_efectivo_fau', 'titular_pago')) {
            Schema::table('cont_pagos_efectivo_fau', function (Blueprint $table) {
                $table->dropColumn('titular_pago');
            });
        }

        if (Schema::hasColumn('cont_pagos_efectivo_fll', 'titular_pago')) {
            Schema::table('cont_pagos_efectivo_fll', function (Blueprint $table) {
                $table->dropColumn('titular_pago');
            });
        }

        if (Schema::hasColumn('cont_pagos_efectivo_ftn', 'titular_pago')) {
            Schema::table('cont_pagos_efectivo_ftn', function (Blueprint $table) {
                $table->dropColumn('titular_pago');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_fau', 'titular_pago')) {
            Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
                $table->dropColumn('titular_pago');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_fll', 'titular_pago')) {
            Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
                $table->dropColumn('titular_pago');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_ftn', 'titular_pago')) {
            Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
                $table->dropColumn('titular_pago');
            });
        }
    }
}
