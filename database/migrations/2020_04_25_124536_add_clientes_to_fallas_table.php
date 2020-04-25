<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientesToFallasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fallas', function (Blueprint $table) {
            $table->string('cliente',80)->nullable();
            $table->string('telefono',80)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fallas', function (Blueprint $table) {
            $table->dropColumn('cliente');
            $table->dropColumn('telefono');
        });
    }
}
