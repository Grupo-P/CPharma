<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRhiCandidatosFasesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rhi_candidatos_fases', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rh_candidatos_id');
            $table->unsignedInteger('rh_fases_id');
            $table->string('user');
            $table->timestamps();

            $table->foreign('rh_candidatos_id')
            ->references('id')
            ->on('rh_candidatos')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('rh_fases_id')
            ->references('id')
            ->on('rh_fases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('rhi_candidatos_fases');
    }
}
