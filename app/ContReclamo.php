<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContReclamo extends Model
{
    public function proveedor()
    {
        return $this->belongsTo('compras\ContProveedor', 'id_proveedor');
    }

    public function getSignoMonedaAttribute($value)
    {
        if ($this->proveedor->moneda == 'Dólares') {
            return 'USD';
        }

        if ($this->proveedor->moneda == 'Bolívares') {
            return 'VES';
        }

        if ($this->proveedor->moneda == 'Euros') {
            return 'EUR';
        }

        if ($this->proveedor->moneda == 'Pesos') {
            return 'COP';
        }
    }

    public function scopeSede($query, $sede)
    {
        if ($sede != 'GRUPO P, C.A') {
            $query->where('sede', $sede);
        }
    }
}
