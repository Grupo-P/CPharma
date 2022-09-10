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
        Schema::create('core_parametros', function (Blueprint $table) {
            $table->id();
            $table->text('variable');
            $table->text('valor');
            $table->text('descripcion')->nullable();            
            $table->tinyInteger('activo')->default(1);
            $table->tinyInteger('borrado')->default(0);
            $table->foreignId('user_created_at')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_updated_at')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_deleted_at')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_parametros');
    }
};
