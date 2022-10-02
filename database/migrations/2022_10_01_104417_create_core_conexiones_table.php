<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_conexiones', function (Blueprint $table) {
            $table->id();
            $table->text('nombre');
            $table->text('nombre_mostrar')->nullable();
            $table->string('siglas',6);
            $table->text('ip_address')->nullable();
            $table->text('driver_db');
            $table->text('instancia_db');
            $table->text('usuario');
            $table->text('clave');
            $table->text('db_online');
            $table->text('db_offline')->nullable();
            $table->tinyInteger('online')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->tinyInteger('activo')->default(1);
            $table->tinyInteger('borrado')->default(0);
            $table->foreignId('user_created_at')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_updated_at')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_deleted_at')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_conexiones');
    }
};
