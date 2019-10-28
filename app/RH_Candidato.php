<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class RH_Candidato extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rh_candidatos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'nombres', 'apellidos', 'cedula', 'direccion', 'telefono_celular',
    	'telefono_habitacion', 'correo', 'como_nos_contacto', 'experiencia_laboral',
    	'observaciones', 'tipo_relacion', 'relaciones_laborales', 'estatus', 'user'
    ];
}
