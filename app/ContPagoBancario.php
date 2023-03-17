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

    public function getSignoMonedaAttribute()
    {
        if ($this->banco->moneda == 'Dólares') {
            return 'USD';
        }

        if ($this->banco->moneda == 'Bolívares') {
            return 'VES';
        }

        if ($this->banco->moneda == 'Euros') {
            return 'EUR';
        }

        if ($this->banco->moneda == 'Pesos') {
            return 'COP';
        }
    }

    public function scopeCantidad($query, $cantidad)
    {
        if ($cantidad == 'Todos') {
            return $query->get();
        }

        return $query->paginate($cantidad);
    }

    public function scopeFechaInicio($query, $fechaInicio)
    {
        if ($fechaInicio) {
            return $query->whereDate('created_at', '>=', $fechaInicio);
        }
    }

    public function scopeFechaFin($query, $fechaFin)
    {
        if ($fechaFin) {
            return $query->whereDate('created_at', '<=', $fechaFin);
        }
    }

    public function scopeNumeroRegistro($query, $numeroRegistro)
    {
        if ($numeroRegistro) {
            return $query->where('id', 'LIKE', '%' . $numeroRegistro . '%');
        }
    }
}
