<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Surtido extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'control', 'sku', 'unidades', 'tipo_surtido', 'estatus', 'operador_generado', 'operador_procesado', 'fecha_generado', 'fecha_procesado', 'comentario', 'created_at', 'updated_at'
    ];

    public function detalle()
    {
        return $this->hasMany('compras\SurtidoDetalle', 'control');
    }

    public function getPrimeroAttribute()
    {
        $detalle = SurtidoDetalle::where('control', $this->control)
            ->orderBy('descripcion')
            ->first();

        return $detalle->descripcion;
    }
}
