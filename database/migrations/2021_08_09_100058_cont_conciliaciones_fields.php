<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContConciliacionesFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
            $table->string('fecha_conciliado')->nullable;
            $table->string('usuario_conciliado')->nullable;
        });

        Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
            $table->string('fecha_conciliado')->nullable;
            $table->string('usuario_conciliado')->nullable;
        });

        Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
            $table->string('fecha_conciliado')->nullable;
            $table->string('usuario_conciliado')->nullable;
        });

        Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
            $table->string('fecha_conciliado')->nullable;
            $table->string('usuario_conciliado')->nullable;
        });

        Schema::table('cont_pagos_efectivo_fau', function (Blueprint $table) {
            $table->string('fecha_conciliado')->nullable;
            $table->string('usuario_conciliado')->nullable;
        });

        Schema::table('cont_pagos_efectivo_ftn', function (Blueprint $table) {
            $table->string('fecha_conciliado')->nullable;
            $table->string('usuario_conciliado')->nullable;
        });

        Schema::table('cont_pagos_efectivo_fll', function (Blueprint $table) {
            $table->string('fecha_conciliado')->nullable;
            $table->string('usuario_conciliado')->nullable;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('cont_pagos_bancarios', 'fecha_conciliado')) {
            Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
                $table->dropColumn('fecha_conciliado');
            });
        }

        if (Schema::hasColumn('cont_pagos_bancarios', 'usuario_conciliado')) {
            Schema::table('cont_pagos_bancarios', function (Blueprint $table) {
                $table->dropColumn('usuario_conciliado');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_fau', 'fecha_conciliado')) {
            Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
                $table->dropColumn('fecha_conciliado');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_fau', 'usuario_conciliado')) {
            Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
                $table->dropColumn('usuario_conciliado');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_ftn', 'fecha_conciliado')) {
            Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
                $table->dropColumn('fecha_conciliado');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_ftn', 'usuario_conciliado')) {
            Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
                $table->dropColumn('usuario_conciliado');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_fll', 'fecha_conciliado')) {
            Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
                $table->dropColumn('fecha_conciliado');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_fll', 'usuario_conciliado')) {
            Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
                $table->dropColumn('usuario_conciliado');
            });
        }
    }
}
