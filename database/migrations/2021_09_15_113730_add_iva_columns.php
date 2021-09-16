<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIvaColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_proveedores', function (Blueprint $table) {
            $table->string('moneda_iva')->nullable();
            $table->string('saldo_iva')->default(0);
        });

        Schema::table('cont_deudas', function (Blueprint $table) {
            $table->string('monto_iva')->default(0);
        });

        Schema::table('cont_reclamos', function (Blueprint $table) {
            $table->string('monto_iva')->default(0);
        });

        Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
            $table->string('monto_proveedor')->default(0);
        });

        Schema::table('cont_pagos_efectivo_ftn', function (Blueprint $table) {
            $table->string('monto_proveedor')->default(0);
        });

        Schema::table('cont_pagos_efectivo_fau', function (Blueprint $table) {
            $table->string('monto_proveedor')->default(0);
        });

        Schema::table('cont_pagos_efectivo_fll', function (Blueprint $table) {
            $table->string('monto_proveedor')->default(0);
        });

        Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
            $table->string('monto_proveedor')->default(0);
        });

        Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
            $table->string('monto_proveedor')->default(0);
        });

        Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
            $table->string('monto_proveedor')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('cont_proveedores', 'moneda_iva')) {
            Schema::table('cont_proveedores', function (Blueprint $table) {
                $table->dropColumn('moneda_iva');
            });
        }

        if (Schema::hasColumn('cont_proveedores', 'saldo_iva')) {
            Schema::table('cont_proveedores', function (Blueprint $table) {
                $table->dropColumn('saldo_iva');
            });
        }

        if (Schema::hasColumn('cont_deudas', 'monto_iva')) {
            Schema::table('cont_deudas', function (Blueprint $table) {
                $table->dropColumn('monto_iva');
            });
        }

        if (Schema::hasColumn('cont_reclamos', 'monto_iva')) {
            Schema::table('cont_reclamos', function (Blueprint $table) {
                $table->dropColumn('monto_iva');
            });
        }

        if (Schema::hasColumn('cont_pagos_bancarios', 'monto_proveedor')) {
            Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
                $table->dropColumn('monto_proveedor');
            });
        }
    }
}
