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
    protected $table = 'rh_entrevistas';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'fecha_entrevista', 'entrevistadores', 'lugar', 'observaciones', 'user'
    ];

}
