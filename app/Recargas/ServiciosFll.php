<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class ServiciosFll extends Model
{
    protected $connection = 'fllRecargas';
    protected $table = 'servicios';
    protected $fillable = [
        "nombre",
        "servicio",        
        "subservicio",
        "minimo",
        "maximo",
        "multiplo",
        "maximo_diario",
        "mensaje",
        "comision",
        "activo",
    ];

    //Buscar registros activos
    public static function activos()
    {
        return static::query()->Where('activo', '=', '1');
    }

    public static function search($search)
    {
        return empty($search) ? static::query() : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('nombre', 'like', '%'.$search.'%');                
    }
}
