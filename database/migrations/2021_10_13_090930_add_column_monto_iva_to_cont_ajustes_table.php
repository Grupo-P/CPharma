<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMontoIvaToContAjustesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_ajustes', function (Blueprint $table) {
            $table->string('monto_iva')->after('monto')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cont_ajustes', function (Blueprint $table) {
            if (Schema::hasColumn('cont_ajustes', 'monto_iva')) {
                Schema::table('cont_ajustes', function (Blueprint $table) {
                    $table->dropColumn('monto_iva');
                });
            }
        });
    }
}
