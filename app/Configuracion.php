<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'variable', 'descripcion','valor','estatus','user'
    ];
}
