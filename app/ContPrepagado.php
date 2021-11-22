<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class ContPrepagado extends Model
{
    public function proveedor()
    {
        return $this->belongsTo('compras\ContProveedor', 'id_proveedor');
    }
}
