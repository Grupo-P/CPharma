<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class CajaKd73 extends Model
{
    protected $connection = 'kd73Recargas';
    protected $table = 'cajas';
    protected $fillable = [
        "nombre",
        "ip",
        "activo",
    ];
}
