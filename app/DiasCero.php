<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class DiasCero extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_articulo', 'codigo_articulo', 'descripcion', 'existencia', 'fecha_captura', 'user'
    ];
}
