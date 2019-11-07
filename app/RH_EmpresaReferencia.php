<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class RH_EmpresaReferencia extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rh_empresa_referencias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'nombre_empresa', 'telefono', 'correo', 'direccion', 'user'
    ];
}
