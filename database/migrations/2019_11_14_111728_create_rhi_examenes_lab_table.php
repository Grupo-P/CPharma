<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRhiExamenesLabTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rhi_examenes_lab', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rh_examenes_id');
            $table->unsignedInteger('rh_laboratorio_id');
            $table->string('user');
            $table->timestamps();

            $table->foreign('rh_examenes_id')
            ->references('id')
            ->on('rh_examenes')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('rh_laboratorio_id')
            ->references('id')
            ->on('rh_laboratorio')
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
        Schema::dropIfExists('rhi_examenes_lab');
    }
}
