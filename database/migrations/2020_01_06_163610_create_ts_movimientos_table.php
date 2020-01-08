<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsMovimientosTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('ts_movimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tasa_ventas_id');
            $table->double('ingresos', 16, 2)->nullable();
            $table->double('egresos', 16, 2)->nullable();
            $table->double('saldo_anterior', 16, 2);
            $table->double('saldo_actual', 16, 2);
            $table->string('concepto');
            $table->date('fecha');
            $table->string('user');
            $table->timestamps();

            $table->foreign('tasa_ventas_id')
            ->references('id')
            ->on('tasa_ventas')
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
        Schema::dropIfExists('ts_movimientos');
    }
}
