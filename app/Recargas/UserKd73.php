<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class UserKd73 extends Model
{
    //users
    protected $connection = 'kd73Recargas';
    protected $table = 'users';
    protected $fillable = [
        'username','name', 'email', 'password',
    ];
}
