<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo', 'codigo_app', 'nombre', 'estatus', 'user'
    ];
}
