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

    public function categoria()
    {
        return $this->belongsTo('compras\Categoria', 'codigo_categoria');
    }

    public function subcategoria()
    {
        return $this->belongsTo('compras\Subcategoria', 'codigo_subcategoria', 'codigo');
    }

    public function scopeBusqueda($query, $clave, $valor)
    {
        if ($clave != '' && $valor != '') {
            if ($clave == 'Codigo interno') {
                $query->where('codigo_interno', 'LIKE', "%$valor%");
            }

            if ($clave == 'Codigo de barra') {
                $query->where('codigo_barra', 'LIKE', "%$valor%");
            }

            if ($clave == 'Descripcion') {
                $query->where('descripcion', 'LIKE', "%$valor%");
            }

            if ($clave == 'Marca') {
                $query->where('marca', 'LIKE', "%$valor%");
            }

            if ($clave == 'Categoria') {
                $query->whereHas('categoria', function ($query) use ($valor) {
                    $query->where('nombre', 'LIKE', "%$valor%");
                });
            }

            if ($clave == 'Subcategoria') {
                $query->whereHas('subcategoria', function ($query) use ($valor) {
                    $query->where('nombre', 'LIKE', "%$valor%");
                });
            }
        }
    }
}
