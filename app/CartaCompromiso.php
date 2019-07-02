<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class CartaCompromiso extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'articulo', 'lote', 'fecha_de_vencimiento', 'proveedor', 'fecha_de_recepcion', 'fecha_de_tope', 'causa', 'nota', 'estatus', 'user'
    ];
}
