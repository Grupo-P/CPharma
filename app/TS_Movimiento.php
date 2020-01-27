<?php

namespace compras;

use Illuminate\Database\Eloquent\Model;

class TS_Movimiento extends Model {
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ts_movimientos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ingresos', 'egresos', 'diferido', 'saldo_anterior', 'saldo_actual',
    	'diferido_anterior', 'diferido_actual', 'concepto'
    ];
}
