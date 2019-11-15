<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class RH_ContactoEmp extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rh_contactos_empresas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'nombre', 'apellido', 'telefono', 'correo', 'cargo', 'user'
    ];
}
