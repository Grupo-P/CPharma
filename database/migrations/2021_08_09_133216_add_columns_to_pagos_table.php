<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_ajustes', function (Blueprint $table) {
            $table->string('usuario_registro')->nullable()->change();
        });

        Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
            $table->string('tasa')->nullable();
            $table->string('autorizado_por')->nullable();
            $table->string('estatus_conciliaciones')->nullable();
        });

        Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
            $table->string('tasa')->nullable();
            $table->string('autorizado_por')->nullable();
            $table->string('estatus_conciliaciones')->nullable();
        });

        Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
            $table->string('tasa')->nullable();
            $table->string('autorizado_por')->nullable();
            $table->string('estatus_conciliaciones')->nullable();
        });

        Schema::table('cont_pagos_efectivo_fau', function (Blueprint $table) {
            $table->string('tasa')->nullable();
            $table->string('autorizado_por')->nullable();
            $table->string('estatus_conciliaciones')->nullable();
        });

        Schema::table('cont_pagos_efectivo_ftn', function (Blueprint $table) {
            $table->string('tasa')->nullable();
            $table->string('autorizado_por')->nullable();
            $table->string('estatus_conciliaciones')->nullable();
        });

        Schema::table('cont_pagos_efectivo_fll', function (Blueprint $table) {
            $table->string('tasa')->nullable();
            $table->string('autorizado_por')->nullable();
            $table->string('estatus_conciliaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('cont_ajustes', 'usuario_registro')) {
            Schema::table('cont_ajustes', function (Blueprint $table) {
                $table->dropColumn('usuario_registro');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_fau', 'tasa')) {
            Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
                $table->dropColumn('tasa');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_fau', 'autorizado_por')) {
            Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
                $table->dropColumn('autorizado_por');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_fau', 'estatus_conciliaciones')) {
            Schema::table('cont_pagos_bolivares_fau', function (Blueprint $table) {
                $table->dropColumn('estatus_conciliaciones');
            });
        }


        if (Schema::hasColumn('cont_pagos_bolivares_ftn', 'tasa')) {
            Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
                $table->dropColumn('tasa');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_ftn', 'autorizado_por')) {
            Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
                $table->dropColumn('autorizado_por');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_ftn', 'estatus_conciliaciones')) {
            Schema::table('cont_pagos_bolivares_ftn', function (Blueprint $table) {
                $table->dropColumn('estatus_conciliaciones');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_fll', 'tasa')) {
            Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
                $table->dropColumn('tasa');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_fll', 'autorizado_por')) {
            Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
                $table->dropColumn('autorizado_por');
            });
        }

        if (Schema::hasColumn('cont_pagos_bolivares_fll', 'estatus_conciliaciones')) {
            Schema::table('cont_pagos_bolivares_fll', function (Blueprint $table) {
                $table->dropColumn('estatus_conciliaciones');
            });
        }




        if (Schema::hasColumn('cont_pagos_dolares_fau', 'tasa')) {
            Schema::table('cont_pagos_dolares_fau', function (Blueprint $table) {
                $table->dropColumn('tasa');
            });
        }

        if (Schema::hasColumn('cont_pagos_dolares_fau', 'autorizado_por')) {
            Schema::table('cont_pagos_dolares_fau', function (Blueprint $table) {
                $table->dropColumn('autorizado_por');
            });
        }

        if (Schema::hasColumn('cont_pagos_dolares_fau', 'estatus_conciliaciones')) {
            Schema::table('cont_pagos_dolares_fau', function (Blueprint $table) {
                $table->dropColumn('estatus_conciliaciones');
            });
        }


        if (Schema::hasColumn('cont_pagos_dolares_ftn', 'tasa')) {
            Schema::table('cont_pagos_dolares_ftn', function (Blueprint $table) {
                $table->dropColumn('tasa');
            });
        }

        if (Schema::hasColumn('cont_pagos_dolares_ftn', 'autorizado_por')) {
            Schema::table('cont_pagos_dolares_ftn', function (Blueprint $table) {
                $table->dropColumn('autorizado_por');
            });
        }

        if (Schema::hasColumn('cont_pagos_dolares_ftn', 'estatus_conciliaciones')) {
            Schema::table('cont_pagos_dolares_ftn', function (Blueprint $table) {
                $table->dropColumn('estatus_conciliaciones');
            });
        }

        if (Schema::hasColumn('cont_pagos_dolares_fll', 'tasa')) {
            Schema::table('cont_pagos_dolares_fll', function (Blueprint $table) {
                $table->dropColumn('tasa');
            });
        }

        if (Schema::hasColumn('cont_pagos_dolares_fll', 'autorizado_por')) {
            Schema::table('cont_pagos_dolares_fll', function (Blueprint $table) {
                $table->dropColumn('autorizado_por');
            });
        }

        if (Schema::hasColumn('cont_pagos_dolares_fll', 'estatus_conciliaciones')) {
            Schema::table('cont_pagos_dolares_fll', function (Blueprint $table) {
                $table->dropColumn('estatus_conciliaciones');
            });
        }
    }
}
