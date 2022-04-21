<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBultosFieldsToTrasladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traslados', function (Blueprint $table) {
            $table->string('bultos_refrigerados')->after('bultos')->default(0);
            $table->string('bultos_fragiles')->after('bultos_refrigerados')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('traslados', function (Blueprint $table) {
            $table->dropColumn('bultos_refrigerados');
            $table->dropColumn('bultos_fragiles');
        });
    }
}
