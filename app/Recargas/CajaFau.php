<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class CajaFau extends Model
{
    //cajas
    protected $connection = 'devRecargas';
    protected $table = 'cajas';
    protected $fillable = [
        "nombre",
        "ip",
        "activo",
    ];
}
