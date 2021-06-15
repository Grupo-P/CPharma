<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContPagoBancario extends Model
{

    protected $table = 'cont_pagos_bancarios';

    public function proveedor()
    {
        return $this->belongsTo('compras\ContProveedor', 'id_proveedor');
    }

    public function banco()
    {
        return $this->belongsTo('compras\ContBanco', 'id_banco');
    }
}
