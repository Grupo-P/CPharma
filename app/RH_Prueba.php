<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class RH_Prueba extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rh_pruebas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'tipo_prueba',
    	'nombre_prueba',
    	'user'
    ];
}
