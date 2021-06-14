<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_proveedores', function (Blueprint $table) {
            $table->string('usuario_creado')->nullable()->after('saldo');
            $table->text('correo_electronico')->nullable()->after('saldo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cont_proveedores', function (Blueprint $table) {
            $table->dropColumn('usuario_creado');
            $table->dropColumn('correo_electronico');
        });
    }
}
