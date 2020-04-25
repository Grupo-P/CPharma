<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_articulo', 'codigo_interno', 'codigo_barra','divisor','unidad_minima','estatus','user','articulo'
    ];
}
