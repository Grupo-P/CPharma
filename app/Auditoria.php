<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','accion', 'tabla','registro', 'user'
    ];
}
