<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContProveedor extends Model
{
    use SoftDeletes;

    protected $fillable = ['nombre_proveedor', 'nombre_representante', 'rif_ci', 'direccion', 'tasa', 'plan_cuenta', 'moneda', 'saldo', 'created_at', 'updated_at'];

    protected $table = 'cont_proveedores';
}
