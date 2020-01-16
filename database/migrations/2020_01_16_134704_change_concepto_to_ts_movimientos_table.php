<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeConceptoToTsMovimientosTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('ts_movimientos', function (Blueprint $table) {
            $table->text('concepto')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('ts_movimientos', function (Blueprint $table) {
            $table->string('concepto')->change();
        });
    }
}
