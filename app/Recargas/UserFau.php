<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class UserFau extends Model
{
    //users
    protected $connection = 'devRecargas';
    protected $table = 'users';
    protected $fillable = [
        'username','name', 'email', 'password',
    ];
}
