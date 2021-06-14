<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToContReclamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cont_reclamos', function (Blueprint $table) {
            $table->string('sede')->nullable()->after('numero_documento');
            $table->string('comentario')->nullable()->after('sede');
            $table->string('usuario_registro')->nullable()->after('comentario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cont_reclamos', function (Blueprint $table) {
            $table->dropColumn('sede');
            $table->dropColumn('comentario');
            $table->dropColumn('usuario_registro');
        });
    }
}
