<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiasCerosTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('dias_ceros', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo_articulo');
            $table->integer('id_articulo');
            $table->string('descripcion');
            $table->decimal('existencia',16,4);
            $table->date('fecha_captura');
            $table->string('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('dias_ceros');
    }
}
