<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToContPagosBancariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
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
        Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
            if (Schema::hasColumn('cont_pagos_bancarios', 'iva')) {
                Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
                    $table->dropColumn('iva');
                });
            }

            if (Schema::hasColumn('cont_pagos_bancarios', 'retencion_deuda_1')) {
                Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
                    $table->dropColumn('retencion_deuda_1');
                });
            }

            if (Schema::hasColumn('cont_pagos_bancarios', 'retencion_deuda_2')) {
                Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
                    $table->dropColumn('retencion_deuda_2');
                });
            }

            if (Schema::hasColumn('cont_pagos_bancarios', 'retencion_iva')) {
                Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
                    $table->dropColumn('retencion_iva');
                });
            }
        });
    }
}
