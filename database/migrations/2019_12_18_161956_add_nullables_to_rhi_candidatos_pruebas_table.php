<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullablesToRhiCandidatosPruebasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rhi_candidatos_pruebas', function (Blueprint $table) {
            $table->text('observaciones')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rhi_candidatos_pruebas', function (Blueprint $table) {
            $table->text('observaciones')->change();
        });
    }
}
