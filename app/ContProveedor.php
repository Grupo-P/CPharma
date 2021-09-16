<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContProveedor extends Model
{
    protected $table = 'cont_proveedores';

    protected $fillable = [
        'nombre_proveedor',
        'nombre_representante',
        'rif_ci',
        'correo_electronico',
        'direccion',
        'tasa',
        'plan_cuenta',
        'moneda',
        'saldo',
        'moneda_iva',
        'saldo_iva',
        'usuario_creado',
        'created_at',
        'updated_at'
    ];
}
