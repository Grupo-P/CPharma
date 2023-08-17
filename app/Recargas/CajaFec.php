<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class CajaFec extends Model
{
    protected $connection = 'fecRecargas';
    protected $table = 'cajas';
    protected $fillable = [
        "nombre",
        "ip",
        "activo",
    ];
}
