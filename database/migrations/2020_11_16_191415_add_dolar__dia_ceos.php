<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDolarDiaCeos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dias_ceros', function (Blueprint $table) {
            $table->float('precio_dolar',16,2)->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dias_ceros', function (Blueprint $table) {
            $table->dropColumn('precio_dolar');
        });
    }
}
