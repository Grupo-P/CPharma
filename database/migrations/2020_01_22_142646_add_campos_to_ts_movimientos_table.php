<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCamposToTsMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ts_movimientos', function (Blueprint $table) {
            $table->string('user_up')->after('user')->nullable();
            $table->double('diferido_anterior', 16, 2)->after('saldo_actual')->nullable();
            $table->double('diferido_actual', 16, 2)->after('diferido_anterior')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_movimientos', function (Blueprint $table) {
            $table->dropColumn([
                'user_up', 
                'diferido_anterior', 
                'diferido_actual'
            ]);
        });
    }
}
