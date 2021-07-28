<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToContDeudasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_deudas', function (Blueprint $table) {
            $table->string('dias_credito')->nullable()->after('sede');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cont_deudas', function (Blueprint $table) {
            $table->dropColumn('dias_credito');
        });
    }
}
