<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class RH_ExamenesM extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rh_examenes';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'estado', 'observaciones'
    ];

}
