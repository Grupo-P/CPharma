<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class RH_Practica extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rh_practicas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'lider', 'lugar', 'duracion', 'observaciones'
    ];
}
