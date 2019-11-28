<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class RH_Fase extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rh_fases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'nombre_fase'
    ];
}
