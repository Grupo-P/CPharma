<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Falla extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'falla', 'usuario','estacion','cliente','telefono'
    ];
}
