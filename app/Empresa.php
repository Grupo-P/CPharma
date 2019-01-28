<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'rif', 'telefono','direccion','estatus','user','observacion'
    ];
}
