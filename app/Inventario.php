<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'codigo','origen_conteo','motivo_conteo','cantidades_conteo','unidades_conteo','estatus','operador_generado','fecha_generado','operador_anulado','fecha_anulado','operador_revisado','fecha_revisado','comentario'
  ];
}
