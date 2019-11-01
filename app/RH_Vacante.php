<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class RH_Vacante extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rh_vacantes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'nombre_vacante', 'departamento', 'turno', 'dias_libres', 'sede',
    	'cantidad', 'fecha_solicitud', 'fecha_limite', 'nivel_urgencia',
    	'solicitante', 'comentarios', 'user'
    ];
}
