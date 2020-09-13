<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo_categoria','codigo', 'nombre', 'estatus', 'user'
    ];
}
