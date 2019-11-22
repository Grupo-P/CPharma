<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class RH_Convocatoria extends Model
{
   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rh_convocatoria';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'fecha', 'lugar', 'cargo_reclutar', 'user'
    ];
 
}
