<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class OperacionFau extends Model
{
    protected $connection = 'fauRecargas';
    protected $table = 'operacions';

    protected $fillable = [
        "id_user",
        "id_caja",
        "tasa",
        "total_bolivares",
        "total_divisas",
        "total_vuelto",
        "estado",
        "fecha",
    ];

    //obtener los datos del cajero
    public function cajero()
    {
        return $this->hasOne(UserFau::class,'id','id_user');
    }

    //obtener los datos de la caja
    public function caja()
    {
        return $this->hasOne(CajaFau::class,'id','id_caja');
    }
    
    //obtener los datos de auditoria
    public function auditoria()
    {
        return $this->hasMany(AuditoriaFau::class, 'id_operacion', 'id');
    }
}
