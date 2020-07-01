<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class InventarioDetalle extends Model
{
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
	  'codigo_conteo','id_articulo','codigo_articulo','codigo_barra','descripcion','existencia_actual','conteo','re_conteo','operador_conteo','fecha_conteo','operador_reconteo','fecha_reconteo'
	];
}
