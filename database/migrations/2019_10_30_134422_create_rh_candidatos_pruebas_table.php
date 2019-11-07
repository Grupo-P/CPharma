<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRhCandidatosPruebasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rhi_candidatos_pruebas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rh_candidatos_id');
            $table->unsignedInteger('rh_pruebas_id');
            $table->string('user');
            $table->timestamps();

            $table->foreign('rh_candidatos_id')
            ->references('id')
            ->on('rh_candidatos')
            ->onDelete('cascade');

            $table->foreign('rh_pruebas_id')
            ->references('id')
            ->on('rh_pruebas')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('rhi_candidatos_pruebas');
    }
}
