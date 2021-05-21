<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContBanco extends Model
{
    use SoftDeletes;

    protected $table = 'cont_bancos';
}
