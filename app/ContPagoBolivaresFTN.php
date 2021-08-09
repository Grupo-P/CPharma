<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class ContPagoBolivaresFTN extends Model
{
    protected $table = 'cont_pagos_bolivares_ftn';

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
        return 'VES';
    }
}
