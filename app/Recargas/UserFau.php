<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class UserFau extends Model
{
    //users
    protected $connection = 'fauRecargas';
    protected $table = 'users';
    protected $fillable = [
        'username','name', 'email', 'password',
    ];
}
