<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class TrackImagen extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo_barra', 'url_app', 'estatus', 'user'
    ];
}
