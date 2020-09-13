<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Categorizacion extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_articulo', 'codigo_interno', 'codigo_barra', 'descripcion', 'marca', 
        'codigo_categoria', 'codigo_subcategoria', 'estatus', 'user'
    ];
}
