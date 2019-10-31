<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeDataToRhEntrevistasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rh_entrevistas', function (Blueprint $table) {
            $table->text('observaciones')->after('lugar')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rh_entrevistas', function (Blueprint $table) {
            $table->string('observaciones')->after('lugar')->change();
        });
    }
}
