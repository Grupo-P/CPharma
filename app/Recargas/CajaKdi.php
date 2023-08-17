<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class CajaKdi extends Model
{
    protected $connection = 'kdiRecargas';
    protected $table = 'cajas';
    protected $fillable = [
        "nombre",
        "ip",
        "activo",
    ];
}
