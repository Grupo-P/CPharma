<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditoriaPmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditoria_pm', function (Blueprint $table) {
            $table->increments('id');
            $table->text("paso");
            $table->longText('informacion')->nullable();
            $table->text("caja")->nullable();
            $table->text("nro_factura")->nullable();
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
        Schema::dropIfExists('auditoria_pm');
    }
}
