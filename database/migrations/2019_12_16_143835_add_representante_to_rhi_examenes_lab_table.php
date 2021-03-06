<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRepresentanteToRhiExamenesLabTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('rhi_examenes_lab', function (Blueprint $table) {
            $table->string('representante')->after('rh_laboratorio_id');
            $table->string('cargo')->after('representante');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('rhi_examenes_lab', function (Blueprint $table) {
            $table->dropColumn([
                'representante', 'cargo'
            ]);
        });
    }
}
