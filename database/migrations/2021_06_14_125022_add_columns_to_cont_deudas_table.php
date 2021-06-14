<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToContDeudasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_deudas', function (Blueprint $table) {
            $table->string('usuario_registro')->nullable()->after('numero_documento');
            $table->string('sede')->nullable()->after('usuario_registro');
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
            $table->dropColumn('usuario_registro');
            $table->dropColumn('sede');
        });
    }
}
