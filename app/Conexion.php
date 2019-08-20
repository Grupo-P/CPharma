<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Conexion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'instancia', 'base_datos', 'usuario', 'credencial', 'estatus', 'user'
    ];
}
