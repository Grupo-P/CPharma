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
      'codigo','origen_conteo','numero_ajuste','motivo_conteo','cantidades_conteo','unidades_conteo','estatus','operador_generado','fecha_generado','operador_anulado','fecha_anulado','operador_revisado','fecha_revisado','comentario'
  ];

  public function scopeEstado($query, $estado)
  {
    $estado = strtoupper($estado);

    if ($estado != 'TODOS') {
        $query->where('estatus', $estado);
    }
  }

  public function scopeFecha($query, $inicio, $fin)
  {
    if ($inicio != '') {
        $query->where('created_at', '>=', $inicio);
    }

    if ($fin != '') {
        $query->where('created_at', '<=', $fin);
    }
  }

  public function scopeLimite($query, $limite)
  {
    if ($limite != 'Todos') {
        $query->limit($limite);
    }
  }
}
