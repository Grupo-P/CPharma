<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRhPracticasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rh_practicas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rh_candidatos_id');
            $table->string('lider');
            $table->string('lugar');
            $table->double('duracion', 8, 2);
            $table->text('observaciones')->nullable();
            $table->string('user');
            $table->string('estatus');
            $table->timestamps();

            $table->foreign('rh_candidatos_id')
            ->references('id')
            ->on('rh_candidatos')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('rh_practicas');
    }
}
