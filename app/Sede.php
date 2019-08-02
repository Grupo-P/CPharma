<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rif', 'razon_social','siglas','direccion','estatus','user'
    ];
}
