<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrecioToDiasCerosTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('dias_ceros', function (Blueprint $table) {
            $table->float('precio',16,2)->after('existencia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('dias_ceros', function (Blueprint $table) {
            $table->dropColumn('precio');
        });
    }
}
