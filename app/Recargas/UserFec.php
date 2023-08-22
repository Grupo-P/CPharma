<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class UserFec extends Model
{
    //users
    protected $connection = 'fecRecargas';
    protected $table = 'users';
    protected $fillable = [
        'username','name', 'email', 'password',
    ];
}
