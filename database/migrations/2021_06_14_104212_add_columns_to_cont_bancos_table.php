<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToContBancosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_bancos', function (Blueprint $table) {
            $table->string('moneda')->nullable()->after('alias_cuenta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cont_bancos', function (Blueprint $table) {
            $table->dropColumn('moneda');
        });
    }
}
