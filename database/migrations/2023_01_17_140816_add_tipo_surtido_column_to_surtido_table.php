<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoSurtidoColumnToSurtidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surtidos', function (Blueprint $table) {
            $table->string('tipo_surtido')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surtidos', function (Blueprint $table) {
            $table->dropColumn('tipo_surtido');
        });
    }
}
