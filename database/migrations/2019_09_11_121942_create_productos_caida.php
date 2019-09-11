<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosCaida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos_caida', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('IdArticulo');
            $table->string('CodigoArticulo');
            $table->string('Descripcion');
            $table->decimal('Precio',16,2);
            $table->string('Existencia');
            $table->string('Dia10');
            $table->string('Dia9');
            $table->string('Dia8');
            $table->string('Dia7');
            $table->string('Dia6');
            $table->string('Dia5');
            $table->string('Dia4');
            $table->string('Dia3');
            $table->string('Dia2');
            $table->string('Dia1');
            $table->string('UnidadesVendidas');
            $table->string('DiasRestantes');
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
    public function down()
    {
        Schema::dropIfExists('productos_caida');
    }
}
