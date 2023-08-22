<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class UserFtn extends Model
{
    //users
    protected $connection = 'ftnRecargas';
    protected $table = 'users';
    protected $fillable = [
        'username','name', 'email', 'password',
    ];
}
