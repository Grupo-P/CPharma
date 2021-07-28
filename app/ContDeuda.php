<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContDeuda extends Model
{
    public function proveedor()
    {
        return $this->belongsTo('compras\ContProveedor', 'id_proveedor');
    }

    public function scopeNumeroDocumento($query, $numero_documento)
    {
        if ($numero_documento != '') {
            return $query->where('numero_documento', 'LIKE', '%' . $numero_documento . '%');
        }
    }

    public function scopeProveedor($query, $id_proveedor)
    {
        if ($id_proveedor != '' && $id_proveedor != 'Todos') {
            return $query->where('id_proveedor', $id_proveedor);
        }
    }

    public function scopeRangoFecha($query, $fechaDesde, $fechaHasta)
    {
        if ($fechaDesde != '' && $fechaHasta != '') {
            return $query->whereDate('created_at', '>=', $fechaDesde)
                ->whereDate('created_at', '<=', $fechaHasta);
        }

        if ($fechaDesde != '') {
            return $query->whereDate('created_at', '>=', $fechaDesde);
        }

        if ($fechaHasta != '') {
            return $query->whereDate('created_at', '<=', $fechaHasta);
        }
    }

    public function scopeRegistradoPor($query, $usuario)
    {
        if ($usuario != '' && $usuario != 'Todos') {
            return $query->where('usuario_registro', $usuario);
        }
    }

    public function scopeSede($query, $sede)
    {
        if ($sede != '' && $sede != 'Todos') {
            return $query->where('sede', $sede);
        }
    }
}
