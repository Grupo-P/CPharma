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
      'control', 'sku', 'unidades', 'estatus', 'operador_generado', 'operador_procesado', 'fecha_generado', 'fecha_procesado', 'comentario', 'created_at', 'updated_at'
    ];

    public function detalle()
    {
        return $this->hasMany('compras\SurtidoDetalle', 'control');
    }
}
