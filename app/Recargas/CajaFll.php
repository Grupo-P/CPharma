<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class CajaFll extends Model
{
    protected $connection = 'fllRecargas';
    protected $table = 'cajas';
    protected $fillable = [
        "nombre",
        "ip",
        "activo",
    ];
}
