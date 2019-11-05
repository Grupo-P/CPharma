<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rh_examenes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('empresa');
            $table->string('representante');
            $table->string('estado');
            $table->text('observaciones')->nullable();
            $table->string('user');
            $table->string('estatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rh_examenes');
    }
}
