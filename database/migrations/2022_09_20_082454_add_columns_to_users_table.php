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
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->string('documento')->nullable()->unique();
            $table->tinyInteger('cambio_clave')->default(0);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('documento');
            $table->dropColumn('cambio_clave');
            $table->dropColumn('activo');
            $table->dropColumn('borrado');
            $table->dropColumn('user_created_at');
            $table->dropColumn('user_updated_at');
            $table->dropColumn('user_deleted_at');
        });
    }
};
