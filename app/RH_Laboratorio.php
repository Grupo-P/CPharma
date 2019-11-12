<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class RH_Laboratorio extends Model {
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rh_laboratorio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    'rif', 'nombre', 'direccion', 'fecha', 'user', 'estatus'
    ];
}
