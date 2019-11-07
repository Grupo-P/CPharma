<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRhCandidatosEntrevistasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rhi_candidatos_entrevistas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rh_candidatos_id');
            $table->unsignedInteger('rh_entrevistas_id');
            $table->string('user');
            $table->timestamps();

            $table->foreign('rh_candidatos_id')
            ->references('id')
            ->on('rh_candidatos')
            ->onDelete('cascade');

            $table->foreign('rh_entrevistas_id')
            ->references('id')
            ->on('rh_entrevistas')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('rhi_candidatos_entrevistas');
    }
}
