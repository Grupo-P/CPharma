<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class ContPagoEfectivoCDD extends Model
{
    protected $table = 'cont_pagos_efectivo_cdd';

    public function scopeSede($query, $sede)
    {
        if ($sede != '') {
            $query->where('sede', $sede);
        }

        $query->where('sede', auth()->user()->sede);
    }

    public function scopeFecha($query, $fechaDesde, $fechaHasta)
    {
        if ($fechaDesde != '') {
            $query->whereDate('created_at', '>=', $fechaDesde);
        }

        if ($fechaHasta != '') {
            $query->whereDate('created_at', '<=', $fechaHasta);
        }
    }

    public function cuenta()
    {
        return $this->belongsTo('compras\ContCuenta', 'id_cuenta');
    }

    public function proveedor()
    {
        return $this->belongsTo('compras\ContProveedor', 'id_proveedor');
    }

    public function getSignoMonedaAttribute()
    {
        return 'USD';
    }

    public function scopeCantidad($query, $cantidad)
    {
        if ($cantidad == 'Todos') {
            return $query->get();
        }

        return $query->paginate($cantidad);
    }
}
