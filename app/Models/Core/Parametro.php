<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    use HasFactory;

    protected $table = "core_parametros";

    protected $fillable = [
        'variable', 'valor' ,'descripcion', 'activo' ,'borrado', 
        'user_created_at', 'user_updated_at', 'user_deleted_at'
    ];
}
