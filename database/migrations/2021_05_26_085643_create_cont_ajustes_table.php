<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContAjustesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cont_ajustes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_proveedor');
            $table->string('monto');
            $table->text('comentario');
            $table->string('reverso')->nullable();
            $table->string('usuario_registro')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('cont_ajustes');
    }
}
