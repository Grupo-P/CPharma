<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class CajaFsm extends Model
{
    protected $connection = 'fmRecargas';
    protected $table = 'cajas';
    protected $fillable = [
        "nombre",
        "ip",
        "activo",
    ];
}
