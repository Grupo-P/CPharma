<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class OrdenCompraDetalle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo_orden','id_articulo','codigo_articulo','codigo_barra','descripcion','existencia_actual','sede1','sede2','sede3','sede4','total_unidades','costo_unitario','costo_total','existencia_rpt','dias_restantes_rpt','origen_rpt','rango_rpt','estatus','user'
    ];
}
