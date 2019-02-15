<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class Dolar extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha', 'tasa', 'user'
    ];
}
