<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Traslado extends Model
{
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id','numero_ajuste','fecha_ajuste','fecha_traslado','sede_emisora','sede_destino','operador_ajuste','operador_traslado','estatus','bultos','tasa','fecha_tasa'
    ];

    public function scopeEstado($query, $estado)
    {
        if ($estado == 'PROCESADO') {
            $query->where('estatus', $estado);
        }

        if ($estado == 'ENTREGADO') {
            $query->where('estatus', $estado);
        }

        if ($estado == 'EMBALADO') {
            $query->where('estatus', $estado);
        }
    }
}
