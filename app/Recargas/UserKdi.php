<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class UserKdi extends Model
{
    //users
    protected $connection = 'kdiRecargas';
    protected $table = 'users';
    protected $fillable = [
        'username','name', 'email', 'password',
    ];
}
