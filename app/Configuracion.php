<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'variable', 'descripcion','valor','estatus','user','contabilidad'
    ];

    public function scopeType($query)
    {
        if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' || $_SERVER['SERVER_NAME'] == 'cpharmagp.com') {
            return $query->where('contabilidad', 1);
        } else {
            return $query->where('contabilidad', null);
        }
    }
}
