<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrectSomeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_deudas', function (Blueprint $table) {
            $table->string('monto_iva')->nullable()->change();
        });

        Schema::table('cont_reclamos', function (Blueprint $table) {
            $table->string('monto_iva')->nullable()->change();
        });

        Schema::table('cont_ajustes', function (Blueprint $table) {
            $table->string('monto')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
