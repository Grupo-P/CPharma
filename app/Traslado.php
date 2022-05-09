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
      'id','numero_ajuste', 'fecha_ajuste', 'fecha_traslado', 'sede_emisora', 'sede_destino', 'operador_ajuste', 'operador_traslado', 'estatus', 'bultos', 'bultos_refrigerados', 'bultos_fragiles', 'tasa', 'fecha_tasa'
    ];

    public function detalle()
    {
        return $this->hasMany(TrasladoDetalle::class, 'id_traslado', 'numero_ajuste')->orderBy('descripcion');
    }

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

    public function scopeLimite($query, $limite)
    {
        if ($limite && $limite != 'Todos') {
            return $query->limit($limite);
        }

        return $query->limit(50);
    }
}
