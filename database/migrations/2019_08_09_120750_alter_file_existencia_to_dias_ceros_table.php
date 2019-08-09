<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFileExistenciaToDiasCerosTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('dias_ceros', function (Blueprint $table) {
            $table->integer('existencia')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('dias_ceros', function (Blueprint $table) {
            $table->decimal('existencia',16,4)->change();
        });
    }
}
