<?php

namespace compras;

use compras\Traslado;
use Illuminate\Database\Eloquent\Model;

class TrasladoDetalle extends Model
{
    protected $table = 'traslados_detalle';

    public function traslado()
    {
        return $this->belongsTo(Traslado::class, 'id_traslado', 'numero_ajuste');
    }
}
