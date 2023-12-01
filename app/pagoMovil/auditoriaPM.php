<?php

namespace compras\pagoMovil;

use Illuminate\Database\Eloquent\Model;

class auditoriaPM extends Model
{
    protected $table="auditoria_pm";

    protected $fillable=[
        'paso',
        'informacion',
        'caja',
        'nro_factura'
    ];
}
