<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class UserFll extends Model
{
    //users
    protected $connection = 'fllRecargas';
    protected $table = 'users';
    protected $fillable = [
        'username','name', 'email', 'password',
    ];
}
