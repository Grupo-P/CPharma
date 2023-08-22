<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class UserFsm extends Model
{
    //users
    protected $connection = 'fmRecargas';
    protected $table = 'users';
    protected $fillable = [
        'username','name', 'email', 'password',
    ];
}
