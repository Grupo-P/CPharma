<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class VueltoVDC extends Model
{
    protected $table = 'vuelto_vdc';

    protected $fillable = [
        'fecha_hora', 
        'id_factura', 
        'banco_cliente', 
        'cedula_cliente', 
        'telefono_cliente', 
        'estatus', 
        'confirmacion_banco',
        'motivo_error',
        'sede',
        'caja',
        'monto',
        'tasaVenta',
        'montoPagado',
        'cedulaClienteFactura',
        'nombreClienteFactura',
        'nombreCajeroFactura',
        'totalFacturaBs',
        'totalFacturaDolar'
    ];

    public function scopeInicio($query, $inicio)
    {
        if ($inicio) {
            $query->whereDate('fecha_hora', '>=', $inicio);
        }
    }

    public function scopeFin($query, $fin)
    {
        if ($fin) {
            $query->whereDate('fecha_hora', '<=', $fin);
        }
    }

    public function scopeSede($query, $sede)
    {
        if ($sede) {
            $query->where('sede', $sede);
        }
    }
    public function scopeFecha($query,$fecha1,$fecha2) {
    	if ( $fecha1!=null && $fecha2!=null) {
    		return $query->whereBetween('created_at',[ $fecha1.' 00:00:00',$fecha2.' 23:59:59']);
    	}
    }
}
