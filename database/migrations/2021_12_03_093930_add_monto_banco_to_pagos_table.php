<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMontoBancoToPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
            $table->string('monto_banco')->default(0);
        });

        Schema::table('cont_pagos_efectivo_ftn', function (Blueprint $table) {
            $table->string('monto_banco')->default(0);
        });

        Schema::table('cont_pagos_efectivo_fau', function (Blueprint $table) {
            $table->string('monto_banco')->default(0);
        });

        Schema::table('cont_pagos_efectivo_fll', function (Blueprint $table) {
            $table->string('monto_banco')->default(0);
        });

        Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
            $table->string('monto_banco')->default(0);
        });

        Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
            $table->string('monto_banco')->default(0);
        });

        Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
            $table->string('monto_banco')->default(0);
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
            $table->dropColumn('monto_banco');
        });

        Schema::table('cont_pagos_efectivo_ftn', function (Blueprint $table) {
            $table->dropColumn('monto_banco');
        });

        Schema::table('cont_pagos_efectivo_fau', function (Blueprint $table) {
            $table->dropColumn('monto_banco');
        });

        Schema::table('cont_pagos_efectivo_fll', function (Blueprint $table) {
            $table->dropColumn('monto_banco');
        });

        Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
            $table->dropColumn('monto_banco');
        });

        Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
            $table->dropColumn('monto_banco');
        });

        Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
            $table->dropColumn('monto_banco');
        });
    }
}
