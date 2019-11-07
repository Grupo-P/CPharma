<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo','moneda','condicion_crediticia','dias_credito','sede_origen','sede_destino','proveedor','fecha_estimada_despacho','numero_factura','observacion','calificacion','montoTotalReal','operador_aprobacion','fecha_aprobacion','operador_recepcion','fecha_recepcion','operador_ingreso','fecha_ingreso','operador_cierre','fecha_cierre','estatus','estado','user'
    ];
}
