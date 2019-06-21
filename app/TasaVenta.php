<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class TasaVenta extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tasa', 'moneda', 'fecha', 'estatus', 'user'
    ];
}
