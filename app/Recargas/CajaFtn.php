<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class CajaFtn extends Model
{
    protected $connection = 'ftnRecargas';
    protected $table = 'cajas';
    protected $fillable = [
        "nombre",
        "ip",
        "activo",
    ];
}
