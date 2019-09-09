<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_articulo', 'codigo_articulo', 'descripcion','condicion','clasificacion','estatus','user'
    ];
}
